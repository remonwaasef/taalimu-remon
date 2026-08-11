<?php

namespace Tests\Feature\Validation;

use App\Http\Requests\Center\StoreStudentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StudentValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $grade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = \App\Models\Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);

        $stage = \App\Models\Stage::create(['tenant_id' => $this->tenant->id, 'name' => 'Stage 1']);
        $this->grade = \App\Models\Grade::create(['tenant_id' => $this->tenant->id, 'stage_id' => $stage->id, 'name' => 'Grade 1']);
    }

    /** @test */
    public function name_must_contain_only_letters()
    {
        $data = [
            'name' => 'John123',
            'phone' => '01234567890',
            'grade_id' => $this->grade->id,
        ];
        $request = new StoreStudentRequest;

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function phone_must_be_valid_format()
    {
        $data = [
            'name' => 'John Doe',
            'phone' => 'abc-123-456',
            'grade_id' => $this->grade->id,
        ];
        $request = new StoreStudentRequest;

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function valid_data_passes_validation()
    {
        $course = \App\Models\Course::create(['tenant_id' => $this->tenant->id, 'title' => 'Group A']);

        $data = [
            'name' => 'رامل محمد',
            'phone' => '01234567890',
            'email' => 'valid@email.com',
            'gender' => 'male',
            'grade_id' => $this->grade->id,
            'course_ids' => [$course->id],
        ];
        $request = new StoreStudentRequest;

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }
}
