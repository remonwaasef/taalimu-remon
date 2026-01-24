<?php

namespace App\DTOs;

class CourseData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?int $instructor_id,
        public readonly ?string $price,
        public readonly ?string $status,
        public readonly ?string $image = null,
        public readonly array $schedules = [],
    ) {}

    /**
     * Create a DTO from an array or request data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            instructor_id: isset($data['instructor_id']) ? (int) $data['instructor_id'] : null,
            price: $data['price'] ?? null,
            status: $data['status'] ?? 'active',
            image: $data['image'] ?? null,
            schedules: $data['schedules'] ?? [],
        );
    }

    /**
     * Convert DTO to array for model creation/update.
     */
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'instructor_id' => $this->instructor_id,
            'price' => $this->price,
            'status' => $this->status,
            'image' => $this->image,
        ], fn($value) => !is_null($value));
    }
}
