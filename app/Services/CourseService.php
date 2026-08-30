<?php

namespace App\Services;

use App\DTOs\CourseData;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Schedule;
use App\Models\Student;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CourseService
{
    protected $certificateService;

    protected $conflictService;

    public function __construct(CertificateService $certificateService, ScheduleConflictService $conflictService)
    {
        $this->certificateService = $certificateService;
        $this->conflictService = $conflictService;
    }

    /**
     * Create a new course.
     *
     * @return Course
     */
    public function createCourse(CourseData $data)
    {
        return DB::transaction(function () use ($data) {
            $course = Course::create($data->toArray());

            // Create Schedules
            if (! empty($data->schedules)) {
                $this->syncSchedules($course, $data->schedules, $data->instructor_id);
            }

            // Notify Admins
            $this->notifyAdmins($course);

            return $course;
        });
    }

    /**
     * Update an existing course.
     *
     * @return Course
     */
    public function updateCourse(Course $course, CourseData $data)
    {
        return DB::transaction(function () use ($course, $data) {
            $course->update($data->toArray());

            // Sync Schedules
            if (isset($data->schedules)) {
                $course->schedules()->delete();
                if (! empty($data->schedules)) {
                    $this->syncSchedules($course, $data->schedules, $data->instructor_id);
                }
            }

            return $course;
        });
    }

    /**
     * Delete a course.
     *
     * @return bool|null
     */
    public function deleteCourse(Course $course)
    {
        return DB::transaction(function () use ($course) {
            // Delete image if exists
            if ($course->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->image);
            }

            return $course->delete();
        });
    }

    /**
     * Enroll a student in a course.
     *
     * @return Enrollment
     *
     * @throws \Exception
     */
    public function enrollStudent(Course $course, Student $student)
    {
        // Check if already enrolled
        $exists = Enrollment::where('user_id', $student->user_id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            throw new \Exception('الطالب مسجل بالفعل في هذا الكورس');
        }

        return Enrollment::create([
            'user_id' => $student->user_id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'active',
            'progress' => 0,
            'remaining_sessions' => $course->sessions_count ?? 0,
        ]);
    }

    /**
     * Mark a lesson as complete for a user in a course.
     */
    public function completeLesson(Course $course, $lessonId, $user)
    {
        $lesson = Lesson::findOrFail($lessonId);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        return DB::transaction(function () use ($course, $lesson, $enrollment) {
            // Mark as progress
            LessonProgress::updateOrCreate([
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ], [
                'completed_at' => now(),
            ]);

            // Update enrollment progress
            $totalLessons = Lesson::whereHas('section', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count();
            $completedLessons = LessonProgress::where('enrollment_id', $enrollment->id)->count();

            $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $enrollment->update(['progress' => $progress]);

            // Check for certificate
            if ($progress >= 100) {
                // إضافة شرط اختبار/مراجعة للشهادة (ليس فقط progress=100%)
                $hasQuizzes = \App\Models\Quiz::whereHas('section', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->exists();

                $passedExams = true;
                if ($hasQuizzes) {
                    $passedExams = \App\Models\QuizAttempt::where('user_id', $user->id)
                        ->whereHas('quiz.section', function ($q) use ($course) {
                            $q->where('course_id', $course->id);
                        })
                        ->where('score', '>=', 50) // Assuming 50 is passing
                        ->exists();
                }

                if ($passedExams) {
                    $this->certificateService->checkAndGenerate($enrollment);
                }
            }

            return $enrollment;
        });
    }

    /**
     * Sync schedules for a course.
     *
     * @throws \Exception if schedule conflicts are detected
     */
    protected function syncSchedules(Course $course, array $schedulesData, $instructorId)
    {
        $dayMapping = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6,
            'الأحد' => 0, 'الاثنين' => 1, 'الثلاثاء' => 2, 'الأربعاء' => 3,
            'الخميس' => 4, 'الجمعة' => 5, 'السبت' => 6,
        ];

        // First, validate all schedules for conflicts
        $allConflicts = [];
        foreach ($schedulesData as $index => $scheduleData) {
            $scheduleData['instructor_id'] = $instructorId;
            $conflicts = $this->conflictService->validateSchedule($scheduleData, $course->id);
            if (! empty($conflicts)) {
                $allConflicts = array_merge($allConflicts, $conflicts);
            }
        }

        // If any conflicts found, throw exception with all messages
        if (! empty($allConflicts)) {
            throw new \Exception(implode("\n", $allConflicts));
        }

        // No conflicts, proceed to save schedules (with internal deduplication)
        $schedules = [];
        $seen = [];
        foreach ($schedulesData as $scheduleData) {
            $dayInput = $scheduleData['day_of_week'];
            $dayValue = $dayMapping[$dayInput] ?? $dayInput;

            // Prevent internal duplicates within same batch (same day + same time)
            $key = $dayValue.'_'.$scheduleData['start_time'].'_'.$scheduleData['end_time'];
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $schedules[] = new Schedule([
                'tenant_id' => \Modules\Tenancy\Services\TenantResolver::get()->id,
                'instructor_id' => $instructorId,
                'classroom_id' => $scheduleData['classroom_id'] ?? null,
                'day_of_week' => $dayValue,
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'max_students' => $scheduleData['max_students'] ?? 50,
            ]);
        }
        $course->schedules()->saveMany($schedules);
    }

    /**
     * Notify admins about course creation.
     */
    protected function notifyAdmins(Course $course)
    {
        $admins = \App\Models\User::where('tenant_id', \Modules\Tenancy\Services\TenantResolver::get()->id)
            ->whereIn('role', ['admin', 'center_admin'])
            ->get();

        Notification::send($admins, new GeneralNotification(
            'course_created',
            'تم إنشاء كورس جديد: '.$course->title,
            route('center.courses.index'),
            'fas fa-book-open',
            auth()->user()->name
        ));
    }
}
