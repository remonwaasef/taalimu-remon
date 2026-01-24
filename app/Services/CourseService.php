<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\DTOs\CourseData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Notifications\GeneralNotification;
use App\Services\CertificateService;
use App\Models\LessonProgress;
use App\Models\Lesson;

class CourseService
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Create a new course.
     *
     * @param CourseData $data
     * @return Course
     */
    public function createCourse(CourseData $data)
    {
        return DB::transaction(function () use ($data) {
            $course = Course::create($data->toArray());

            // Create Schedules
            if (!empty($data->schedules)) {
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
     * @param Course $course
     * @param CourseData $data
     * @return Course
     */
    public function updateCourse(Course $course, CourseData $data)
    {
        return DB::transaction(function () use ($course, $data) {
            $course->update($data->toArray());

            // Sync Schedules
            if (isset($data->schedules)) {
                $course->schedules()->delete();
                if (!empty($data->schedules)) {
                    $this->syncSchedules($course, $data->schedules, $data->instructor_id);
                }
            }

            return $course;
        });
    }

    /**
     * Delete a course.
     *
     * @param Course $course
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
     * @param Course $course
     * @param Student $student
     * @return Enrollment
     * @throws \Exception
     */
    public function enrollStudent(Course $course, Student $student)
    {
        // Check if already enrolled
        $exists = Enrollment::where('user_id', $student->user_id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            throw new \Exception('الطالب مسجل بالفعل في هذه الدورة');
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
            $totalLessons = Lesson::whereHas('section', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count();
            $completedLessons = LessonProgress::where('enrollment_id', $enrollment->id)->count();

            
            $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $enrollment->update(['progress' => $progress]);

            // Check for certificate
            if ($progress >= 100) {
                $this->certificateService->checkAndGenerate($enrollment);
            }

            return $enrollment;
        });
    }


    /**
     * Sync schedules for a course.
     */
    protected function syncSchedules(Course $course, array $schedulesData, $instructorId)
    {
        $dayMapping = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6,
            'Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
            'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6,
        ];

        $schedules = [];
        foreach ($schedulesData as $scheduleData) {
            $dayInput = $scheduleData['day_of_week'];
            $dayValue = $dayMapping[$dayInput] ?? $dayInput;

            $schedules[] = new Schedule([
                'tenant_id' => app('tenant')->id,
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
        $admins = \App\Models\User::where('tenant_id', app('tenant')->id)
            ->whereIn('role', ['admin', 'center_admin'])
            ->get();
            
        Notification::send($admins, new GeneralNotification(
            'course_created',
            "تم إضافة دورة جديدة: {$course->title}",
            route('center.courses.index'),
            'fas fa-book-open',
            auth()->user()->name
        ));
    }
}
