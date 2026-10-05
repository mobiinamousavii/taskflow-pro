@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg style=" backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.05);">
                <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info">➕ ایجاد تسک جدید</h5>
                    <a href="{{ route('projects.show', $selectedProjectId ?? 1) }}" class="btn btn-outline-light btn-sm">بازگشت</a>
                </div>

                <div class="card-body">
                    {{-- نمایش خطاهای کلی اعتبارسنجی --}}
                    @if ($errors->any())
                    <div class="alert alert-danger bg-danger text-white border-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        {{-- انتخاب پروژه --}}
                        <div class="mb-3">
                            <label for="project_id" class="form-label text-light">انتخاب پروژه <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select bg-dark text-white border-secondary @error('project_id') is-invalid @enderror" required onchange="this.form.submit()">
                                <option value="" disabled {{ !$selectedProject ? 'selected' : '' }}>یک پروژه انتخاب کنید...</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ (old('project_id', $selectedProjectId) == $project->id) ? 'selected' : '' }}>
                                    {{ $project->name }} (ورک‌اسپیس: {{ $project->workspace->name ?? 'عمومی' }})
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- انتخاب وضعیت داینامیک تسک (ProjectStatus) --}}
                        <div class="mb-3">
                            <label for="project_status_id" class="form-label text-light">وضعیت تسک <span class="text-danger">*</span></label>
                            <select name="project_status_id" id="project_status_id" class="form-select bg-dark text-white border-secondary @error('project_status_id') is-invalid @enderror" required>
                                @if(isset($statuses) && $statuses->count() > 0)
                                <option value="" disabled selected>وضعیت تسک را انتخاب کنید...</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('project_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                                @endforeach
                                @else
                                <option value="" disabled selected>ابتدا پروژه را انتخاب کنید یا وضعیت برای این ورک‌اسپیس تعریف نشده است</option>
                                @endif
                            </select>
                            @error('project_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- عنوان تسک --}}
                        <div class="mb-3">
                            <label for="title" class="form-label text-light">عنوان تسک <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control bg-dark text-white border-secondary @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="مثلاً: طراحی فرم لاگین" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- توضیحات تسک --}}
                        <div class="mb-3">
                            <label for="description" class="form-label text-light">توضیحات تسک</label>
                            <textarea name="description" id="description" rows="4" class="form-control bg-dark text-white border-secondary @error('description') is-invalid @enderror" placeholder="جزییات و نکات مربوط به این تسک را بنویسید...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تاریخ مهلت انجام (Due Date) --}}
                        <div class="mb-4">
                            <label for="due_date" class="form-label text-light">مهلت انجام (Due Date)</label>
                            <input type="date" name="due_date" id="due_date" class="form-control bg-dark text-white border-secondary @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                            @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- دکمه ذخیره --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info font-weight-bold text-dark">
                                🚀 ثبت و ایجاد تسک
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection