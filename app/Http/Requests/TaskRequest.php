<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        $project = $this->route('project');
        $projectid = $project instanceof Project ? $project->id : $project;

        return [
            'status_id' => [
                'required',
                Rule::exists('project_statuses', 'id')->where('project_id', $projectid),
            ],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'assignee_id' => [
                'nullable',
                Rule::exists('project_users', 'user_id')->where('project_id', $projectid)
            ],
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:0',
        ];
    }

    #[Override]
    public function messages()
    {
        return [
        ];
    }
}