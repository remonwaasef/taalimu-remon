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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'instructor_id' => 'nullable|exists:instructors,id',
            'price' => 'nullable|numeric|min:0',
            'sessions_count' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,published',
            'image' => 'nullable|image|max:2048',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'nullable|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required|after:schedules.*.start_time',
            'schedules.*.classroom_id' => 'nullable|exists:classrooms,id',
            'schedules.*.max_students' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان الكورس مطلوب.',
            'instructor_id.required' => 'يجب اختيار مدرس.',
            'schedules.required' => 'يجب إضافة جدول واحد على الأقل.',
            'schedules.*.day_of_week.required' => 'يوم الجدولة مطلوب.',
            'schedules.*.start_time.required' => 'وقت البدء مطلوب.',
            'schedules.*.end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البدء.',
            'schedules.*.classroom_id.required' => 'يجب اختيار القاعة.',
        ];
    }
}
