<?php

namespace Tests\Feature\Validation;

use App\Http\Requests\Center\StoreStudentRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function name_must_contain_only_letters()
    {
        $data = ['name' => 'John123'];
        $request = new StoreStudentRequest();
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function phone_must_be_valid_format()
    {
        $data = ['phone' => 'abc-123-456'];
        $request = new StoreStudentRequest();
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function valid_data_passes_validation()
    {
        $data = [
            'name' => 'رامل محمد',
            'phone' => '01234567890',
            'email' => 'valid@email.com',
            'gender' => 'male',
        ];
        $request = new StoreStudentRequest();
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertFalse($validator->fails());
    }
}
