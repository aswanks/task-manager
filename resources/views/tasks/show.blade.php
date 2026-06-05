@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-white">Task Detail + AI Summary</h1>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('tasks.create') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            + New Task
        </a>
        @endif
    </div>
    <!-- Filters bar -->
   <div class="flex flex-wrap items-center gap-3 mb-2">
    <form method="GET" class="flex flex-wrap gap-4 mb-8" action="{{ route('tasks.index') }}" id="filterForm">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search Filter Task"
                class="bg-white rounded-xl pl-12 pr-4 py-3 w-72 border border-gray-200 focus:ring-2 focus:ring-blue-500">
        </div>
        <select name="status" class="bg-white rounded-xl px-5 py-3" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>

                    <select class="bg-white rounded-xl px-5 py-3" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Medium</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>

                    <select name="priority" class="bg-white rounded-xl px-5 py-3" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
    </form>
</div>
    <p class="text-xs text-gray-500 mb-6">Filter User Task</p>

    <!-- Main grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Task detail card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 text-gray-900">

                <!-- Card header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            {{ $task->title }}
                        </h2>
                        <!-- Status & Priority badges -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full border border-gray-300
                                             text-xs font-semibold text-gray-700 bg-white">
                                    Status
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full border border-gray-300
                                             text-xs font-semibold text-gray-700 bg-white">
                                    Priority
                                </span>
                                <span class="text-xs
                                    @if($task->priority->value === 'high') text-red-500
                                    @elseif($task->priority->value === 'medium') text-yellow-500
                                    @else text-green-500 @endif">
                                    {{ ucfirst($task->priority->value) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600 p-1 ml-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="19" cy="12" r="1.5"/>
                        </svg>
                    </button>
                </div>

                <!-- Description section -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Description</h3>

                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        Assigned to:
                        <span class="font-normal text-gray-500">
                            {{ $task->user?->name ?? 'Not assigned' }}
                        </span>
                    </p>

                    <!-- Due date field -->
                    <div class="relative mb-3">
                        <div class="flex items-center justify-between rounded-xl border border-gray-200
                                    bg-white px-4 py-2.5 text-sm text-gray-500">
                            <span>Due Date: {{ $task->due_date?->format('Y-m-d') ?? 'N/A' }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $task->description ?: 'No description available.' }}
                    </p>
                </div>

                <!-- AI Generated Summary box -->
                <div class="border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-2">AI-Generated Summary</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $task->ai_summary ?? 'No AI summary generated yet.' }}
                    </p>
                </div>

                <!-- AI Summary line -->
                <div class="mb-6 text-sm text-gray-700">
                    <span class="font-semibold">AI Summary:</span>
                    <span class="text-gray-500 ml-1">
                        {{ $task->ai_summary ? \Illuminate\Support\Str::limit($task->ai_summary, 80) : 'N/A' }}
                    </span>
                    @if($task->ai_priority)
                    <span class="ml-1 font-semibold">Priority:</span>
                    <span class="ml-1
                        @if($task->ai_priority->value === 'high') text-red-500
                        @elseif($task->ai_priority->value === 'medium') text-yellow-500
                        @else text-green-500 @endif">
                        {{ ucfirst($task->ai_priority->value) }}
                    </span>
                    @endif
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex gap-3">
                        @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium
                                  px-5 py-2 rounded-full transition-colors">
                            Edit Task
                        </a>
                        @endcan
                        @can('delete', $task)
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this task?')"
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium
                                           px-5 py-2 rounded-full transition-colors">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </div>
                    <a href="{{ route('tasks.index') }}"
                       class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                        ← Back to tasks
                    </a>
                </div>

            </div>
        </div>

        <!-- Right sidebar -->
        <div class="space-y-5">

            <!-- User card -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl">

                <div class="p-6 border-b">

                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
                             class="w-14 h-14 rounded-full">

                        <div>
                            <h4 class="font-semibold">
                                {{ auth()->user()->name }}
                            </h4>
                            <p class="text-gray-500 text-sm">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>
                    </div>

                </div>

                <div class="p-4 space-y-3">

                    <div class="bg-blue-500 text-white rounded-xl px-4 py-3">
                        Tasks
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="px-4 py-2">
                        Users
                    </div>
                    @endif

                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-3 rounded-xl bg-dark-100 text-gray-700
                                    hover:bg-red-500 hover:text-white transition duration-200">
                            Logout
                        </button>
                    </form>

                </div>
                <div class="p-6 border-t">

                    <div class="grid grid-cols-3 gap-3 text-center">

                        <div>
                            <canvas id="totalTasksChart" width="80" height="80"></canvas>
                            <p class="text-xs mt-2 text-gray-600">Total Tasks</p>
                        </div>

                        <div>
                            <canvas id="completedTasksChart" width="80" height="80"></canvas>
                            <p class="text-xs mt-2 text-gray-600">Completed</p>
                        </div>

                        <div>
                            <canvas id="pendingTasksChart" width="80" height="80"></canvas>
                            <p class="text-xs mt-2 text-gray-600">Pending</p>
                        </div>

                    </div>

                </div>
                <!-- Chart -->
                <div class="bg-white rounded-3xl p-5 text-dark text-center">
    
                    <h3 class="font-semibold mb-5">
                        Monthly Task Completion
                    </h3>

                    <div class="flex justify-center">
                        <canvas id="taskChart1" class="max-w-[250px]"></canvas>
                    </div>

                </div>
            </div>

            <!-- Refresh AI Summary button -->
            <form method="POST" action="{{ route('tasks.ai-refresh', $task) }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-white hover:bg-gray-50 text-blue-500 font-medium
                               text-sm px-4 py-3 rounded-2xl border border-gray-200
                               transition-colors">
                    Refresh AI Summary
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </form>

            <!-- Bar chart card -->
            <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700">
                <h3 class="text-sm font-semibold text-white mb-4">Monthly Task Completion</h3>
                <canvas id="taskChart" height="160"></canvas>
            </div>

        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('taskChart1'),{
    type:'bar',
    data:{
        labels:['Jan','Feb','Mar','Apr','May'],
        datasets:[{
            data:[12,18,22,8,16],
            backgroundColor:'#3B82F6',
            borderRadius:10
        }]
    },
    options:{
        plugins:{legend:{display:false}},
        scales:{
            x:{ticks:{color:'#fff'}},
            y:{ticks:{color:'#fff'}}
        }
    }
});

new Chart(document.getElementById('taskChart'),{
    type:'bar',
    data:{
        labels:['Jan','Feb','Mar','Apr','May'],
        datasets:[{
            data:[12,18,22,8,16],
            backgroundColor:'#3B82F6',
            borderRadius:10
        }]
    },
    options:{
        plugins:{legend:{display:false}},
        scales:{
            x:{ticks:{color:'#fff'}},
            y:{ticks:{color:'#fff'}}
        }
    }
});


function createCircleChart(id, value, total, color)
{
    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [value, total - value],
                backgroundColor: [
                    color,
                    '#E5E7EB'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '75%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        },
        plugins: [{
            id: 'text',
            beforeDraw(chart) {

                let width = chart.width;
                let height = chart.height;
                let ctx = chart.ctx;

                ctx.restore();

                let fontSize = (height / 4).toFixed(2);

                ctx.font = fontSize + "px sans-serif";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#111827";

                let text = value;
                let textX =
                    Math.round((width - ctx.measureText(text).width) / 2);

                let textY = height / 2;

                ctx.fillText(text, textX, textY);

                ctx.save();
            }
        }]
    });
}

createCircleChart(
    'totalTasksChart',
    {{ $stats['total'] }},
    {{ max($stats['total'],1) }},
    '#3B82F6'
);

createCircleChart(
    'completedTasksChart',
    {{ $stats['completed'] }},
    {{ max($stats['total'],1) }},
    '#10B981'
);

createCircleChart(
    'pendingTasksChart',
    {{ $stats['pending'] }},
    {{ max($stats['total'],1) }},
    '#EF4444'
);

</script>
@endsection