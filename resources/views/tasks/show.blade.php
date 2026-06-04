@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Task Details
        </h1>

        <div class="flex gap-3">
            @can('update', $task)
            <a href="{{ route('tasks.edit', $task) }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Edit Task
            </a>
            @endcan

            <a href="{{ route('tasks.index') }}"
               class="bg-gray-200 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">

        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $task->title }}
            </h2>
        </div>

        <div class="p-6 space-y-6">

            <!-- Description -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                    Description
                </h3>

                <p class="text-gray-700 dark:text-gray-300">
                    {{ $task->description ?: 'No description available.' }}
                </p>
            </div>

            <!-- Task Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Assigned To
                    </h3>

                    <p class="text-gray-900 dark:text-white">
                        {{ $task->user?->name ?? 'Not Assigned' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Due Date
                    </h3>

                    <p class="text-gray-900 dark:text-white">
                        {{ $task->due_date?->format('M d, Y') ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Priority
                    </h3>

                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($task->priority->value === 'high')
                            bg-red-100 text-red-700
                        @elseif($task->priority->value === 'medium')
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst($task->priority->value) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Status
                    </h3>

                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($task->status->value === 'completed')
                            bg-green-100 text-green-700
                        @elseif($task->status->value === 'in_progress')
                            bg-blue-100 text-blue-700
                        @else
                            bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Created At
                    </h3>

                    <p class="text-gray-900 dark:text-white">
                        {{ $task->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                        Last Updated
                    </h3>

                    <p class="text-gray-900 dark:text-white">
                        {{ $task->updated_at->format('M d, Y h:i A') }}
                    </p>
                </div>

            </div>

        </div>

        @can('delete', $task)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this task?')"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Delete Task
                </button>
            </form>
        </div>
        @endcan

    </div>

</div>
@endsection