<x-app-layout>
  <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h1>
      <div class="flex gap-2">
        @can('update', $task)
        <a href="{{ route('tasks.edit', $task) }}"
          class="rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white">Edit</a>
        @endcan
        @can('delete', $task)
        <form method="POST" action="{{ route('tasks.destroy', $task) }}">
          @csrf @method('DELETE')
          <button class="rounded-lg bg-red-600 text-white px-4 py-2 text-sm hover:bg-red-700">Delete</button>
        </form>
        @endcan
      </div>
    </div>

    <!-- Details card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
      <p class="text-gray-700 dark:text-gray-300">{{ $task->description }}</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
          <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_',' ',$task->status->value)) }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Priority</p>
          <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ ucfirst($task->priority->value) }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Assigned to</p>
          <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $task->user?->name ?? '—' }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Due date</p>
          <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $task->due_date?->format('M j, Y') ?? '—' }}</p>
        </div>
      </div>
    </div>

    <!-- AI Summary card -->
    <div class="bg-indigo-50 dark:bg-indigo-950 border border-indigo-100 dark:border-indigo-800 rounded-xl p-6 space-y-3">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wide">AI summary</h2>
        <form method="POST" action="{{ route('tasks.ai-refresh', $task) }}">
          @csrf
          <button class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Refresh</button>
        </form>
      </div>
      <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $task->ai_summary ?? 'No summary yet.' }}</p>
      @if($task->ai_priority)
      <p class="text-xs text-indigo-500 dark:text-indigo-400">AI recommended priority: <strong>{{ ucfirst($task->ai_priority->value) }}</strong></p>
      @endif
    </div>

  </div>
</x-app-layout>