<?php

// QA DB helper: called with a command arg from qa_*.mjs via CLI bootstrap.
// Returns JSON. Local-only tool, never deployed.

$autoload = __DIR__ . '/vendor/autoload.php';
require $autoload;
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$cmd = $argv[1] ?? '';
$out = execute($cmd);
echo is_string($out) ? $out : json_encode($out, JSON_UNESCAPED_UNICODE);
echo PHP_EOL;

function execute(string $cmd): mixed
{
    switch (true) {
        case $cmd === 'lesson1':
            $row = DB::table('lessons')->where('tenant_id', 1)->orderBy('id')->first();
            return $row ? (string) $row->id : '0';

        case $cmd === 'lesson3':
            $row = DB::table('lessons')->where('tenant_id', 3)->orderBy('id')->first();
            return $row ? (string) $row->id : '0';

        case $cmd === 'ensure_t3_lesson':
            $section = DB::table('sections')->where('tenant_id', 3)->orderBy('id')->first();
            if (! $section) {
                $sectionId = DB::table('sections')->insertGetId(['tenant_id' => 3, 'course_id' => 3, 'title' => 'QA Unit A', 'sort_order' => 1]);
            } else {
                $sectionId = $section->id;
            }
            $lesson = DB::table('lessons')->where('tenant_id', 3)->orderBy('id')->first();
            if (! $lesson) {
                $lessonId = DB::table('lessons')->insertGetId(['tenant_id' => 3, 'section_id' => $sectionId, 'title' => 'QA Lesson A', 'type' => 'video', 'sort_order' => 1]);
            } else {
                $lessonId = $lesson->id;
            }
            return (string) $lessonId;

        case $cmd === 'make_t3_quiz_with_question':
            $section = DB::table('sections')->where('tenant_id', 3)->orderBy('id')->first();
            $sectionId = $section ? $section->id : null;
            if (! $sectionId) {
                $sectionId = DB::table('sections')->insertGetId(['tenant_id' => 3, 'course_id' => 3, 'title' => 'QA Unit A', 'sort_order' => 1]);
            }
            $lesson = DB::table('lessons')->where('tenant_id', 3)->orderBy('id')->first();
            $lessonId = $lesson ? $lesson->id : null;
            if (! $lessonId) {
                $lessonId = DB::table('lessons')->insertGetId(['tenant_id' => 3, 'section_id' => $sectionId, 'title' => 'QA Lesson A', 'type' => 'video', 'sort_order' => 1]);
            }
            $quizId = DB::table('quizzes')->insertGetId(['lesson_id' => $lessonId, 'tenant_id' => 3, 'title' => 'اختبار QA مؤقت 2', 'passing_score' => 60, 'duration_minutes' => 10]);
            $qId = DB::table('questions')->insertGetId(['quiz_id' => $quizId, 'tenant_id' => 3, 'content' => 'سؤال QA بروب 1؟', 'type' => 'mcq', 'points' => 10]);
            DB::table('question_options')->insert(['question_id' => $qId, 'content' => 'A', 'is_correct' => 1]);
            DB::table('question_options')->insert(['question_id' => $qId, 'content' => 'B', 'is_correct' => 0]);

            return ['quiz' => $quizId, 'question' => $qId];

        case $cmd === 'set_t3_owner_password':
            $user = DB::table('users')->where('email', 'qa.owner194003@example.com')->first();
            if (! $user) { return 'no-user'; }
            DB::table('users')->where('id', $user->id)->update([
                'password' => Illuminate\Support\Facades\Hash::make('password'),
                'must_change_password' => 0,
                'google2fa_enabled' => 0,
            ]);

            return 'ok';

        case $cmd === 'lastquiz':
            $row = DB::table('quizzes')->where('title', 'like', 'اختبار QA%')->orderByDesc('id')->first();
            return $row ? ['id' => $row->id, 'title' => $row->title, 'passing_score' => $row->passing_score] : null;

        case preg_match('/^quiz(\d+)$/', $cmd, $m) === 1:
            $row = DB::table('quizzes')->where('id', (int) $m[1])->first();
            return $row ? ['id' => $row->id, 'title' => $row->title, 'passing_score' => $row->passing_score] : null;

        case $cmd === 'qoflast':
            $row = DB::table('questions')->orderByDesc('id')->first();
            return $row ? ['id' => $row->id] : null;

        case preg_match('/^q(\d+)$/', $cmd, $m) === 1:
            $row = DB::table('questions')->where('id', (int) $m[1])->first();
            return $row ? ['id' => $row->id, 'content' => $row->content, 'points' => $row->points] : null;

        case preg_match('/^qbyquiz(\d+)$/', $cmd, $m) === 1:
            $rows = DB::table('questions')->where('quiz_id', (int) $m[1])->get(['id', 'content', 'points']);
            return $rows;

        case preg_match('/^qcount$/', $cmd) === 1:
            return (string) DB::table('questions')->count();

        case preg_match('/^opts(\d+)$/', $cmd, $m) === 1:
            $rows = DB::table('question_options')->where('question_id', (int) $m[1])->get(['id', 'content', 'is_correct']);
            return $rows;

        case $cmd === 'set_t3_student_password':
            $user = DB::table('users')->where('email', 'mhmd.aaly.demo0@qa-center-194003')->first();
            if (! $user) { return 'no-user'; }
            DB::table('users')->where('id', $user->id)->update([
                'password' => Illuminate\Support\Facades\Hash::make('password'),
                'must_change_password' => 0,
                'google2fa_enabled' => 0,
            ]);

            return 'ok';

        case $cmd === 'cleanupt3quizzes':
            $ids = DB::table('quizzes')
                ->where('title', 'like', '%QA%')
                ->whereIn('title', ['اختبار QA مؤقت 2', 'اختبار QA مؤقت معدل', 'اختبار QA للطالب'])
                ->pluck('id');
            foreach ($ids as $id) {
                DB::table('quiz_attempts')->where('quiz_id', $id)->delete();
                $qids = DB::table('questions')->where('quiz_id', $id)->pluck('id');
                DB::table('question_options')->whereIn('question_id', $qids)->delete();
                DB::table('questions')->where('quiz_id', $id)->delete();
                DB::table('quizzes')->where('id', $id)->delete();
            }

            return true;

        case preg_match('/^opc(\d+)$/', $cmd, $m) === 1:
            return (string) DB::table('question_options')->where('question_id', (int) $m[1])->count();

        case preg_match('/^lastopt(\d+)$/', $cmd, $m) === 1:
            $row = DB::table('question_options')->where('question_id', (int) $m[1])->orderByDesc('id')->first();
            return $row ? ['id' => $row->id, 'content' => $row->content] : null;

        case preg_match('/^opt(\d+)$/', $cmd, $m) === 1:
            $row = DB::table('question_options')->where('id', (int) $m[1])->first();
            return $row ? ['id' => $row->id, 'content' => $row->content] : null;

        case preg_match('/^oc(\d+)$/', $cmd, $m) === 1:
            $correct = DB::table('question_options')->where('question_id', (int) $m[1])->where('is_correct', 1)->count();
            return ['correct' => $correct];

        case preg_match('/^cleanupquiz(\d+)$/', $cmd, $m) === 1:
            $id = (int) $m[1];
            DB::table('quiz_attempts')->where('quiz_id', $id)->delete();
            $qids = DB::table('questions')->where('quiz_id', $id)->pluck('id');
            DB::table('question_options')->whereIn('question_id', $qids)->delete();
            DB::table('questions')->where('quiz_id', $id)->delete();
            DB::table('quizzes')->where('id', $id)->delete();

            return DB::table('quizzes')->where('id', $id)->exists() ? false : true;

        case $cmd === 'ensure_t3_att_fixtures':
            $branch = DB::table('branches')->where('tenant_id', 3)->where('name', 'like', '%QA%')->orderByDesc('id')->first();
            if (! $branch) {
                $branchId = DB::table('branches')->insertGetId(['tenant_id' => 3, 'name' => 'فرع QA الحضور', 'address' => 'شارع الاختبار', 'phone' => '0100']);
            } else {
                $branchId = $branch->id;
            }
            $classroom = DB::table('classrooms')->where('tenant_id', 3)->where('name', 'like', 'قاعة QA%')->orderByDesc('id')->first();
            if (! $classroom) {
                $classroomId = DB::table('classrooms')->insertGetId(['tenant_id' => 3, 'branch_id' => $branchId, 'name' => 'قاعة QA 1', 'capacity' => 20, 'type' => 'classroom', 'is_active' => 1]);
            } else {
                $classroomId = $classroom->id;
            }
            $instructor = DB::table('instructors')->where('tenant_id', 3)->orderBy('id')->first();
            $schedule = DB::table('schedules')->where('tenant_id', 3)->orderByDesc('id')->first();

            return ['branch' => $branchId, 'classroom' => $classroomId, 'instructor' => $instructor ? $instructor->id : null, 'schedule' => $schedule ? $schedule->id : null];

        case $cmd === 'make_t3_att_schedule':
            $fixture = json_decode(json_encode(execute('ensure_t3_att_fixtures')), true);
            $scheduleId = DB::table('schedules')->insertGetId([
                'tenant_id' => 3,
                'course_id' => 3,
                'classroom_id' => $fixture['classroom'],
                'instructor_id' => $fixture['instructor'],
                'branch_id' => $fixture['branch'],
                'day_of_week' => now()->dayOfWeek,
                'start_time' => now()->subHour()->format('H:i:s'),
                'end_time' => now()->addHour()->format('H:i:s'),
                'max_students' => 20,
            ]);

            return ['schedule' => $scheduleId];

        case preg_match('/^attsched(\d+)$/', $cmd, $m) === 1:
            $rows = DB::table('attendances')->where('schedule_id', (int) $m[1])->get(['id', 'student_id', 'status', 'late_minutes']);
            return $rows;

        case preg_match('/^sched(\d+)$/', $cmd, $m) === 1:
            $row = DB::table('schedules')->where('id', (int) $m[1])->first();
            return $row ? ['id' => $row->id, 'course_id' => $row->course_id, 'classroom_id' => $row->classroom_id, 'day_of_week' => $row->day_of_week, 'start_time' => $row->start_time, 'end_time' => $row->end_time, 'max_students' => $row->max_students] : null;

        case $cmd === 'lastsched':
            $row = DB::table('schedules')->where('tenant_id', 3)->orderByDesc('id')->first();
            return $row ? ['id' => $row->id] : null;

        case preg_match('/^attscount(\d+)$/', $cmd, $m) === 1:
            return (string) DB::table('attendances')->where('schedule_id', (int) $m[1])->whereDate('session_date', today())->count();

        case $cmd === 'cleanupt3att':
            $schedules = DB::table('schedules')->where('tenant_id', 3)->where('course_id', 3)->get(['id', 'classroom_id', 'instructor_id', 'branch_id']);
            foreach ($schedules as $s) {
                DB::table('attendances')->where('schedule_id', $s->id)->delete();
                DB::table('schedules')->where('id', $s->id)->delete();
            }
            $classrooms = DB::table('classrooms')->where('tenant_id', 3)->where('name', 'like', 'قاعة QA%')->pluck('id');
            if ($classrooms->count()) {
                DB::table('classrooms')->whereIn('id', $classrooms)->delete();
            }
            $branches = DB::table('branches')->where('tenant_id', 3)->where('name', 'like', '%QA%')->pluck('id');
            if ($branches->count()) {
                DB::table('branches')->whereIn('id', $branches)->delete();
            }

            return DB::table('schedules')->where('tenant_id', 3)->where('course_id', 3)->exists() ? false : true;

        default:
            return ['error' => 'unknown cmd: ' . $cmd];
    }
}