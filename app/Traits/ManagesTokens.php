<?php

namespace App\Traits;

use App\Constants\TokenAbility;
use Laravel\Sanctum\NewAccessToken;

trait ManagesTokens
{
    /**
     * Create a full-access token for the user.
     *
     * @param string $name
     * @return NewAccessToken
     */
    public function createFullAccessToken(string $name = 'full_access'): NewAccessToken
    {
        return $this->createToken($name, [TokenAbility::ALL]);
    }

    /**
     * Create a student-app restricted token.
     *
     * @param string $name
     * @return NewAccessToken
     */
    public function createStudentAccessToken(string $name = 'student_app'): NewAccessToken
    {
        return $this->createToken($name, [TokenAbility::STUDENT_APP]);
    }

    /**
     * Create an instructor-app restricted token.
     *
     * @param string $name
     * @return NewAccessToken
     */
    public function createInstructorAccessToken(string $name = 'instructor_app'): NewAccessToken
    {
        return $this->createToken($name, [TokenAbility::INSTRUCTOR_APP]);
    }

    /**
     * Create a read-only API token.
     *
     * @param string $name
     * @return NewAccessToken
     */
    public function createReadOnlyAccessToken(string $name = 'readonly_api'): NewAccessToken
    {
        return $this->createToken($name, [TokenAbility::READ_ONLY]);
    }
}
