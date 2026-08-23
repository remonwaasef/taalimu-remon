<?php

namespace App\Services;

use App\Interfaces\VideoProviderInterface;
use App\Models\ClassRecording;
use App\Models\OnlineClass;
use App\Models\OnlineClassParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Domain service for live online classes: lifecycle, Zoom session sync,
 * attendance and recording state.
 */
class OnlineClassService
{
    public function __construct(protected VideoProviderInterface $provider)
    {
    }

    /**
     * Create a class and provision its provider session when possible.
     */
    public function createClass(array $data, int $instructorId, ?int $tenantId): OnlineClass
    {
        return DB::transaction(function () use ($data, $instructorId, $tenantId) {
            $class = OnlineClass::create($data + [
                'instructor_id' => $instructorId,
                'tenant_id' => $tenantId,
                'status' => OnlineClass::STATUS_SCHEDULED,
            ]);

            if ($this->shouldProvision($class)) {
                $this->provisionSession($class);
            }

            $this->syncAllowlist($class, $data['selected_student_ids'] ?? []);

            Log::info('Online class created', ['online_class_id' => $class->id, 'tenant_id' => $class->tenant_id]);

            return $class->refresh();
        });
    }

    public function updateClass(OnlineClass $class, array $data): OnlineClass
    {
        DB::transaction(function () use ($class, $data) {
            if ($this->shouldProvision($class) && ! empty($class->meeting_id)) {
                // Keep the provider session in sync with schedule changes.
                try {
                    app(ZoomService::class)->updateSession($class);
                } catch (\Throwable $e) {
                    Log::warning('Zoom updateSession failed', ['class_id' => $class->id, 'error' => $e->getMessage()]);
                }
            } elseif ($this->shouldProvision($class) && empty($class->meeting_id)) {
                $this->provisionSession($class);
            }

            $class->fill($data);
            $class->save();

            if (array_key_exists('selected_student_ids', $data)) {
                $this->syncAllowlist($class, $data['selected_student_ids']);
            }
        });

        return $class->refresh();
    }

    /**
     * Teacher pressed "start": mark live and stamp started_at.
     */
    public function startClass(OnlineClass $class): OnlineClass
    {
        if (! in_array($class->status, [OnlineClass::STATUS_SCHEDULED, OnlineClass::STATUS_LIVE], true)) {
            throw new \App\Exceptions\BusinessException(__('online_classes::messages.cannot_start'));
        }

        $class->forceFill(['status' => OnlineClass::STATUS_LIVE, 'started_at' => $class->started_at ?? now()])->save();

        Log::info('Online class session started', ['online_class_id' => $class->id]);

        return $class;
    }

    /**
     * Teacher pressed "end": complete the class and finalize attendance.
     */
    public function endClass(OnlineClass $class): OnlineClass
    {
        DB::transaction(function () use ($class) {
            $class->forceFill([
                'status' => OnlineClass::STATUS_COMPLETED,
                'ended_at' => now(),
                'recording_status' => $class->auto_recording && $class->meeting_id
                    ? ClassRecording::STATUS_PROCESSING
                    : $class->recording_status,
            ])->save();

            // Finalize attendance from heartbeats: minutes already tracked.
            OnlineClassParticipant::where('online_class_id', $class->id)
                ->whereNull('left_at')
                ->whereNotNull('joined_at')
                ->update(['status' => 'attended']);

            Log::info('Online class session ended', ['online_class_id' => $class->id]);
        });

        return $class;
    }

    public function cancelClass(OnlineClass $class): void
    {
        if ($this->provider instanceof \App\Services\ZoomService || $class->meeting_id) {
            $this->provider->deleteSession($class);
        }

        $class->forceFill(['status' => OnlineClass::STATUS_CANCELLED])->save();
    }

    /**
     * Student clicked "join" inside Taalimu — open an attendance record and
     * keep it fresh via heartbeats.
     */
    public function markJoined(OnlineClass $class, \App\Models\User $user): OnlineClassParticipant
    {
        $student = $user->student;

        /** @var OnlineClassParticipant|null $participant */
        $participant = OnlineClassParticipant::withoutGlobalScopes()
            ->where('online_class_id', $class->id)
            ->when($student, fn ($q) => $q->where('student_id', $student->id))
            ->first();

        if (! $participant) {
            $participant = new OnlineClassParticipant([
                'tenant_id' => $class->tenant_id,
                'online_class_id' => $class->id,
                'student_id' => $student?->id ?? 0,
                'user_id' => $user->id,
            ]);

            if (! $student) {
                // Viewer without a student profile (admin/instructor preview).
                $participant->student_id = 0;
            }

            $participant->save();
        }

        $participant->forceFill([
            'status' => 'joined',
            'joined_at' => $participant->joined_at ?? now(),
            'left_at' => null,
        ])->save();

        return $participant;
    }

    /**
     * Heartbeat every ~60s while the classroom page is open. Each beat adds a
     * minute of attendance. Also marks left_at on absence at class end.
     */
    public function heartbeat(OnlineClass $class, \App\Models\User $user): int
    {
        $student = $user->student;

        $query = OnlineClassParticipant::query()
            ->where('online_class_id', $class->id)
            ->when($student, fn ($q) => $q->where('student_id', $student->id));

        $updated = $query->increment('attendance_minutes');

        if ($updated === 0) {
            $this->markJoined($class, $user);

            return 1;
        }

        return $updated;
    }

    /**
     * Replace the explicit allowlist for `selected` access mode classes.
     */
    public function syncAllowlist(OnlineClass $class, array $studentIds): void
    {
        if ($class->access_mode !== 'selected') {
            return;
        }

        $students = \App\Models\Student::query()
            ->where('tenant_id', $class->tenant_id)
            ->whereIn('id', $studentIds)
            ->get();

        DB::transaction(function () use ($class, $students) {
            OnlineClassParticipant::where('online_class_id', $class->id)->delete();

            foreach ($students as $student) {
                OnlineClassParticipant::create([
                    'tenant_id' => $class->tenant_id,
                    'online_class_id' => $class->id,
                    'student_id' => $student->id,
                    'user_id' => $student->user_id,
                    'status' => 'invited',
                ]);
            }
        });
    }

    protected function shouldProvision(OnlineClass $class): bool
    {
        return $this->provider->isConfigured()
            && $class->platform === 'zoom'
            && $class->status !== OnlineClass::STATUS_CANCELLED;
    }

    /**
     * Ask the provider for a real session and persist its identifiers.
     */
    protected function provisionSession(OnlineClass $class): void
    {
        try {
            $session = $this->provider->createSession($class);

            $class->forceFill([
                'meeting_id' => $session['external_id'],
                'zoom_meeting_uuid' => $session['external_uuid'],
                'meeting_password' => $session['password'],
                'meeting_link' => $session['join_url'] ?: null,
                'zoom_account_id' => $session['account_id'],
            ])->save();
        } catch (\Throwable $e) {
            // Do not block scheduling; teacher can retry by editing the class.
            Log::error('Zoom session provisioning failed', [
                'online_class_id' => $class->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
