@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit task</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update the task details below.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">

        @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 dark:bg-red-950 border border-red-100 dark:border-red-800 px-4 py-3">
            <ul class="text-sm text-red-700 dark:text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Title <span class="text-red-500">*</span>
                </label>
                <input id="title" name="title" type="text"
                       value="{{ old('title', $task->title) }}" required
                       class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                              bg-white dark:bg-gray-900
                              text-gray-900 dark:text-white
                              border-gray-200 dark:border-gray-700
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              transition-colors
                              @error('title') border-red-400 @enderror">
                @error('title')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                 bg-white dark:bg-gray-900
                                 text-gray-900 dark:text-white
                                 border-gray-200 dark:border-gray-700
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                 transition-colors resize-none
                                 @error('description') border-red-400 @enderror">{{ old('description', $task->description) }}</textarea>
                @error('description')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" name="priority" required
                            class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                   bg-white dark:bg-gray-900
                                   text-gray-900 dark:text-white
                                   border-gray-200 dark:border-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   transition-colors">
                        <option value="low"    @selected(old('priority', $task->priority->value) === 'low')>Low</option>
                        <option value="medium" @selected(old('priority', $task->priority->value) === 'medium')>Medium</option>
                        <option value="high"   @selected(old('priority', $task->priority->value) === 'high')>High</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                   bg-white dark:bg-gray-900
                                   text-gray-900 dark:text-white
                                   border-gray-200 dark:border-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   transition-colors">
                        <option value="pending"     @selected(old('status', $task->status->value) === 'pending')>Pending</option>
                        <option value="in_progress" @selected(old('status', $task->status->value) === 'in_progress')>In progress</option>
                        <option value="completed"   @selected(old('status', $task->status->value) === 'completed')>Completed</option>
                    </select>
                </div>
            </div>

            <!-- Due date & Assigned to -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Due date
                    </label>
                    <input id="due_date" name="due_date" type="date"
                           value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                  bg-white dark:bg-gray-900
                                  text-gray-900 dark:text-white
                                  border-gray-200 dark:border-gray-700
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  transition-colors">
                </div>

                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Assign to
                    </label>
                    <select id="assigned_to" name="assigned_to"
                            class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                   bg-white dark:bg-gray-900
                                   text-gray-900 dark:text-white
                                   border-gray-200 dark:border-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   transition-colors">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}"
                                @selected(old('assigned_to', $task->assigned_to) == $user->id)>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('tasks.show', $task) }}"
                   class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm
                          text-gray-700 dark:text-gray-300
                          hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white
                               hover:bg-indigo-700 active:bg-indigo-800
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                               transition-colors">
                    Save changes
                </button>
            </div>

        </form>
    </div>
</div>
@endsection