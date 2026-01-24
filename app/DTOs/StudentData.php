<?php

namespace App\DTOs;

class StudentData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email,
        public readonly string $phone,
        public readonly ?string $parent_phone = null,
        public readonly ?int $grade_id = null,
        public readonly ?string $code = null,
        public readonly ?string $national_id = null,
        public readonly ?string $birth_date = null,
        public readonly ?string $gender = null,
        public readonly ?string $address = null,
        public readonly ?string $parent_name = null,
        public readonly ?string $parent_job = null,
        public readonly ?string $parent_relation = null,
        public readonly ?string $emergency_phone = null,
        public readonly ?string $school_name = null,
        public readonly ?string $section_type = null,
        public readonly ?string $profile_photo = null,
        public readonly ?string $grade_level = null, // Used for import
    ) {}

    /**
     * Create a DTO from an array or request data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'],
            parent_phone: $data['parent_phone'] ?? null,
            grade_id: isset($data['grade_id']) ? (int) $data['grade_id'] : null,
            code: $data['code'] ?? null,
            national_id: $data['national_id'] ?? null,
            birth_date: $data['birth_date'] ?? null,
            gender: $data['gender'] ?? null,
            address: $data['address'] ?? null,
            parent_name: $data['parent_name'] ?? null,
            parent_job: $data['parent_job'] ?? null,
            parent_relation: $data['parent_relation'] ?? null,
            emergency_phone: $data['emergency_phone'] ?? null,
            school_name: $data['school_name'] ?? null,
            section_type: $data['section_type'] ?? null,
            profile_photo: $data['profile_photo'] ?? null,
            grade_level: $data['grade_level'] ?? null,
        );
    }

    /**
     * Convert DTO to array for model creation/update.
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'parent_phone' => $this->parent_phone,
            'grade_id' => $this->grade_id,
            'code' => $this->code,
            'national_id' => $this->national_id,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'address' => $this->address,
            'parent_name' => $this->parent_name,
            'parent_job' => $this->parent_job,
            'parent_relation' => $this->parent_relation,
            'emergency_phone' => $this->emergency_phone,
            'school_name' => $this->school_name,
            'section_type' => $this->section_type,
            'profile_photo' => $this->profile_photo,
            'grade_level' => $this->grade_level,
        ], fn($value) => !is_null($value));
    }
}
