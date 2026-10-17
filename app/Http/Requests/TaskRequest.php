<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'assignee_id' => ['required', 'exists:project_users,user_id'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable',],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'date'],
        ];
    }
    #[Override]
    public function messages()
    {
        return [
            'project_id.required' => 'انتخاب پروژه الزامیست',
            'title.required' => 'عنوان تسک الزامیست',
        ];
    }
}
