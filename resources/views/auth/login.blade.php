<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Task Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">

<div class="min-h-full flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

    <!-- Logo / heading -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 mb-6">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2
                         M9 5a2 2 0 012-2h2a2 2 0 012 2
                         m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Task Manager</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sign in to your account</p>
    </div>

    <!-- Card -->
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-sm rounded-2xl
                    border border-gray-100 dark:border-gray-700">

            <!-- Session errors -->
            @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 dark:bg-red-950 border border-red-100
                        dark:border-red-800 px-4 py-3">
                <p class="text-sm text-red-700 dark:text-red-400">
                    {{ $errors->first() }}
                </p>
            </div>
            @endif

            <!-- Session status (e.g. password reset confirmation) -->
            @if (session('status'))
            <div class="mb-5 rounded-lg bg-green-50 dark:bg-green-950 border border-green-100
                        dark:border-green-800 px-4 py-3">
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Email address
                    </label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}"
                           autocomplete="email" autofocus required
                           class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                  bg-white dark:bg-gray-900
                                  text-gray-900 dark:text-white
                                  placeholder-gray-400 dark:placeholder-gray-500
                                  border-gray-200 dark:border-gray-700
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  transition-colors
                                  @error('email') border-red-400 dark:border-red-600 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password
                        </label>
                    </div>
                    <input id="password" name="password" type="password"
                           autocomplete="current-password" required
                           class="w-full rounded-lg border px-3.5 py-2.5 text-sm
                                  bg-white dark:bg-gray-900
                                  text-gray-900 dark:text-white
                                  placeholder-gray-400 dark:placeholder-gray-500
                                  border-gray-200 dark:border-gray-700
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  transition-colors
                                  @error('password') border-red-400 dark:border-red-600 @enderror"
                           placeholder="••••••••">
                    @error('password')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center gap-2">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 dark:border-gray-600
                                  text-indigo-600 focus:ring-indigo-500
                                  bg-white dark:bg-gray-900">
                    <label for="remember"
                           class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                        Remember me
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5
                               text-sm font-medium text-white
                               hover:bg-indigo-700 active:bg-indigo-800
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800
                               transition-colors">
                    Sign in
                </button>
            </form>

        </div>

        <!-- Register link -->
        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}"
               class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                Create one
            </a>
        </p>
    </div>
</div>

</body>
</html>