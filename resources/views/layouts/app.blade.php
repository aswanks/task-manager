<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Task Manager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full">

    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <a href="{{ route('tasks.index') }}"
                       class="text-base font-semibold text-gray-900 dark:text-white">
                        Task Manager
                    </a>
                </div>

                <div class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('tasks.index') }}"
                       class="text-sm transition-colors
                              {{ request()->routeIs('tasks.index')
                                 ? 'text-indigo-600 dark:text-indigo-400 font-medium'
                                 : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        Tasks
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('tasks.create') }}"
                       class="text-sm transition-colors
                              {{ request()->routeIs('tasks.create')
                                 ? 'text-indigo-600 dark:text-indigo-400 font-medium'
                                 : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        New task
                    </a>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:block">
                        {{ auth()->user()->name }}
                        <span class="ml-1 px-1.5 py-0.5 rounded text-xs
                            {{ auth()->user()->role === 'admin'
                               ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300'
                               : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ auth()->user()->role }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-500 dark:text-gray-400
                                       hover:text-gray-900 dark:hover:text-white transition-colors">
                            Sign out
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-lg bg-green-50 border border-green-100 px-4 py-3 flex items-center justify-between">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 ml-4 text-lg leading-none">&times;</button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-lg bg-red-50 border border-red-100 px-4 py-3 flex items-center justify-between">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 ml-4 text-lg leading-none">&times;</button>
        </div>
    </div>
    @endif

    <main>
        @yield('content')
    </main>

</div>

</body>
</html>