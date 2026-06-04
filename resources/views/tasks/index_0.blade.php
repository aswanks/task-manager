@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Stats cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total',         'value' => $stats['total']],
            ['label' => 'Completed',      'value' => $stats['completed']],
            ['label' => 'Pending',        'value' => $stats['pending']],
            ['label' => 'High priority',  'value' => $stats['high']],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
            <p class="text-3xl font-semibold text-gray-900 dark:text-white mt-1">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input name="search" value="{{ request('search') }}" placeholder="Search tasks…"
               class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 text-sm
                      bg-white dark:bg-gray-800 dark:text-white">
        <select name="status"
                class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 text-sm
                       bg-white dark:bg-gray-800 dark:text-white">
            <option value="">All statuses</option>
            <option value="pending"     @selected(request('status') === 'pending')>Pending</option>
            <option value="in_progress" @selected(request('status') === 'in_progress')>In progress</option>
            <option value="completed"   @selected(request('status') === 'completed')>Completed</option>
        </select>
        <select name="priority"
                class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 text-sm
                       bg-white dark:bg-gray-800 dark:text-white">
            <option value="">All priorities</option>
            <option value="low"    @selected(request('priority') === 'low')>Low</option>
            <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
            <option value="high"   @selected(request('priority') === 'high')>High</option>
        </select>
        <button class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-indigo-700">
            Filter
        </button>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('tasks.create') }}"
           class="ml-auto bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-indigo-700">
            + New task
        </a>
        @endif
    </form>

    <!-- Task table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">Assigned to</th>
                    <th class="px-5 py-3">Priority</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Due date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                        {{ $task->title }}
                    </td>
                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                        {{ $task->user?->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($task->priority->value === 'high')   bg-red-100 text-red-700
                            @elseif($task->priority->value === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($task->priority->value) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($task->status->value === 'completed')  bg-green-100 text-green-700
                            @elseif($task->status->value === 'in_progress') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                        {{ $task->due_date?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3 flex items-center gap-3">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="text-indigo-600 hover:underline text-sm">View</a>
                        @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="text-gray-500 hover:underline text-sm">Edit</a>
                        @endcan
                        @can('delete', $task)
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-sm"
                                    onclick="return confirm('Delete this task?')">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                        No tasks found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tasks->withQueryString()->links() }}</div>

</div>
@endsection