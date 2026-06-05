@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6">
     <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Task</h1>
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
    <div class=" flex flex-col lg:flex-row gap-6">

        <!-- Main Content -->
        <div class="flex-1 bg-white rounded-2xl p-6 text-gray-900">

             @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Card header -->
                <div class="flex items-start justify-between mb-5">
                    <h2 class="text-xl font-bold text-gray-900 leading-tight">
                        {{ $task->title }}
                    </h2>
                    <button class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                        </svg>
                    </button>
                </div>
            <!-- Filters -->
             <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4 ">
                    @csrf
                    @method('PUT')

                    <!-- Title input with avatar -->
                    <div class="relative">
                        <input id="title" name="title" type="text"
                               value="{{ old('title', $task->title) }}"
                               placeholder="e.g. Launch New Campaign"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                                      text-sm text-gray-700 placeholder-gray-400 pr-12
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full
                                    bg-gray-300 flex items-center justify-center overflow-hidden">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>
                        @error('title')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <textarea id="description" name="description" rows="5"
                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                                         text-sm text-gray-700 placeholder-gray-400
                                         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                         resize-none">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority toggle buttons -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-800 w-16">Priority</span>
                        <div class="flex items-center gap-2">
                            @foreach(['low','medium','high'] as $p)
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="{{ $p }}"
                                       class="sr-only peer"
                                       {{ old('priority', $task->priority->value) === $p ? 'checked' : '' }}>
                                <span class="px-4 py-1.5 rounded-lg border text-sm font-medium transition-colors
                                             border-gray-200 text-gray-500
                                             peer-checked:bg-blue-500 peer-checked:border-blue-500 peer-checked:text-white
                                             hover:border-gray-300">
                                    {{ ucfirst($p) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status pill buttons -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @foreach(['pending' => 'Low','in_progress' => 'Medium','completed' => 'High'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="{{ $val }}"
                                   class="sr-only peer"
                                   {{ old('status', $task->status->value) === $val ? 'checked' : '' }}>
                            <span class="px-4 py-1.5 rounded-full border text-sm font-medium transition-colors
                                         border-gray-200 text-gray-500 bg-gray-50
                                         peer-checked:bg-red-100 peer-checked:border-red-300 peer-checked:text-red-600
                                         hover:border-gray-300">
                                @if(!$loop->first)+ @endif{{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <!-- Due date -->
                    <div class="relative">
                        <input id="due_date" name="due_date" type="date"
                               value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                                      text-sm text-gray-500 pr-12
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <!-- Assign to -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-800">Assign To</span>
                        </div>
                        <div class="relative">
                            <select id="assigned_to" name="assigned_to"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3
                                           text-sm text-gray-500 appearance-none pr-10
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        @selected(old('assigned_to', $task->assigned_to) == $user->id)>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full
                                        border-2 border-gray-300 pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- Save button -->
                    <div class="pt-2 flex justify-center">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium
                                       px-10 py-2.5 rounded-full text-sm transition-colors
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>

                </form>

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