@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Main Content -->
        <div class="flex-1">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-5xl font-bold text-white">
                    Task List
                </h1>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('tasks.create') }}"
                    class="px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-semibold shadow-lg">
                    + New Task
                </a>
                @endif
            </div>

            <!-- Filters -->
            <form method="GET" class="flex flex-wrap gap-4 mb-8">

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

                <select name="status" class="bg-white rounded-xl px-5 py-3">
                    <option value="">Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                 <select name="status" class="bg-white rounded-xl px-5 py-3">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                <select name="priority" class="bg-white rounded-xl px-5 py-3">
                    <option value="">Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

              
            </form>

            <!-- Task Cards -->
            <div class="grid md:grid-cols-2 gap-6">

                @foreach($tasks as $task)

                <div class="bg-white rounded-3xl p-6 shadow-xl">

                    <div class="flex justify-between mb-4">

                        <span class="px-3 py-1 rounded-full text-xs flex items-center gap-2

                            @if($task->status->value === 'pending')
                                bg-yellow-100 text-yellow-700
                            @elseif($task->status->value === 'in_progress')
                                bg-blue-100 text-blue-700
                            @else
                                bg-green-100 text-green-700
                            @endif
                            ">

                        @if($task->status->value === 'pending')
                            <i class="fas fa-clock"></i>
                        @elseif($task->status->value === 'in_progress')
                            <i class="fas fa-spinner"></i>
                        @else
                            <i class="fas fa-check-circle"></i>
                        @endif

                        {{ ucfirst(str_replace('_',' ',$task->status->value)) }}
                    </span>

                        <div>⋯</div>

                    </div>

                    <h2 class="text-2xl font-semibold mb-4">
                        {{ $task->title }}
                    </h2>

                    <div class="flex gap-2 mb-4">

                        <span class="px-3 py-1 rounded-full bg-gray-100 text-xs">
                            Status
                        </span>

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs">
                            Priority {{ ucfirst($task->priority->value) }}
                        </span>

                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit($task->description,120) }}
                    </p>

                    <div class="space-y-2 text-gray-500 text-sm">
                        <p>Assigned: {{ $task->user?->name }}</p>
                        <p>Due: {{ $task->due_date?->format('Y-m-d') }}</p>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">

                        <a href="{{ route('tasks.edit',$task) }}"
                            class="px-5 py-2 rounded-full bg-gray-200">
                            Edit
                        </a>

                        <a href="{{ route('tasks.show',$task) }}"
                            class="px-5 py-2 rounded-full bg-blue-500 text-white">
                            View
                        </a>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-80 space-y-6">

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

                    <div class="px-4 py-2">
                        Logout
                    </div>

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

            <!-- Chart -->
            <div class="bg-slate-900 rounded-3xl p-5 text-white">
                <h3 class="font-semibold mb-5">
                    Monthly Task Completion
                </h3>

                <canvas id="taskChart"></canvas>
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