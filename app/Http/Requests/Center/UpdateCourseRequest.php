<?php

namespace App\Http\Requests\Center;

/**
 * Update uses the same rules, custom validator and messages as Store.
 * Override here only where update semantics need to differ.
 */
class UpdateCourseRequest extends StoreCourseRequest {}
