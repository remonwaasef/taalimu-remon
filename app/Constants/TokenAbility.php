<?php

namespace App\Constants;

class TokenAbility
{
    /**
     * Full access scope.
     */
    public const ALL = '*';

    /**
     * Student mobile application scope.
     * Can view courses, take quizzes, but cannot manage administrative data.
     */
    public const STUDENT_APP = 'app:student';

    /**
     * Instructor mobile application scope.
     * Can manage curriculum and grade assignments.
     */
    public const INSTRUCTOR_APP = 'app:instructor';

    /**
     * External reporting / Read-only scope.
     * Only allows GET requests.
     */
    public const READ_ONLY = 'api:readonly';

    /**
     * Get all defined abilities.
     *
     * @return array<string>
     */
    public static function all(): array
    {
        return [
            self::ALL,
            self::STUDENT_APP,
            self::INSTRUCTOR_APP,
            self::READ_ONLY,
        ];
    }
}
