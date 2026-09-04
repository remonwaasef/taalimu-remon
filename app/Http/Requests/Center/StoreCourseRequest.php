<?php

namespace App\Http\Requests\Center;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor_id' => 'required|exists:instructors,id',
            'price' => 'required|numeric|min:0',
            'sessions_count' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,published',
            'image' => 'nullable|image|max:2048',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required|after:schedules.*.start_time',
            'schedules.*.classroom_id' => 'required|exists:classrooms,id',
            'schedules.*.max_students' => 'nullable|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sessionsCount = (int) $this->input('sessions_count', 0);
            $schedulesCount = is_array($this->input('schedules')) ? count($this->input('schedules')) : 0;

            if ($schedulesCount > 0 && $sessionsCount > 0 && $schedulesCount !== $sessionsCount) {
                $validator->errors()->add('schedules', __('center::courses.validation_schedules_count_mismatch', ['count' => $sessionsCount]));
            }
        });
    }

    public function messages()
    {
        return [
            'title.required' => __('center::courses.validation_title_required'),
            'instructor_id.required' => __('center::courses.validation_instructor_required'),
            'schedules.required' => __('center::courses.validation_schedules_required'),
            'schedules.*.day_of_week.required' => __('center::courses.validation_day_required'),
            'schedules.*.start_time.required' => __('center::courses.validation_start_time_required'),
            'schedules.*.end_time.after' => __('center::courses.validation_end_time_after'),
            'schedules.*.classroom_id.required' => __('center::courses.validation_classroom_required'),
        ];
    }
}
