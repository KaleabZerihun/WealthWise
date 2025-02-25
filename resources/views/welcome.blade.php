<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>WealthWise</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap"
        rel="stylesheet"
    />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans bg-gray-50 text-gray-800 dark:bg-black dark:text-gray-200">
<div class="min-h-screen flex flex-col">
    <!-- Header / Navigation -->
    <header class="w-full py-4 bg-white dark:bg-gray-900 shadow-sm relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <!-- Logo and Brand Name -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
                <span class="text-xl font-bold text-gray-700 dark:text-gray-200">WealthWise</span>
            </div>

            <!-- Register & Login Links -->
            <nav class="absolute right-4 top-4 flex space-x-4">
                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:underline">
                    Login
                </a>
                <a href="{{ route('register') }}" class="text-gray-700 dark:text-gray-300 hover:underline">
                    Register
                </a>
            </nav>
        </div>
    </header>


    <!-- Hero / Introduction Section -->
    <main class="flex-1 w-full">
        <div class="relative overflow-hidden">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-8 lg:space-y-0">
                <!-- Hero Text -->
                <div class="lg:w-1/2 space-y-4">
                    <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white sm:text-5xl">
                        Empower Your Financial Journey
                    </h1>
                    <p class="text-base text-gray-600 dark:text-gray-300 max-w-md">
                        Welcome to WealthWise—a streamlined platform to manage your portfolio, schedule expert guidance, and stay informed on market trends.
                    </p>
                    <div class="flex space-x-4 pt-4">
                        <!-- Example CTA Buttons -->
                        <a href="{{ route('login') }}"
                           class="inline-block bg-gray-800 hover:bg-gray-900 text-white font-medium px-5 py-3 rounded transition">
                            Get Started
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-3 rounded transition">
                            Sign Up
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="lg:w-1/2 flex justify-center">
                    <!-- Example placeholder image (use your own) -->
                    <img
                        src="https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=850&q=80"
                        alt="Financial chart"
                        class="rounded-lg shadow-lg max-w-sm"
                    />
                </div>
            </div>
        </div>

        <!-- Highlights / Features Section -->
        <div class="bg-white dark:bg-gray-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="flex flex-col items-center text-center">
                        <!-- Icon -->
                        <svg
                            class="h-10 w-10 text-gray-700 dark:text-gray-200 mb-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-2">
                            Centralized Dashboard
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Get instant access to portfolio summaries, upcoming advisor appointments, and personalized insights—all in one place.
                        </p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="flex flex-col items-center text-center">
                        <svg
                            class="h-10 w-10 text-gray-700 dark:text-gray-200 mb-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16c0 .55.45 1 1 1h15" />
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-2">
                            Seamless Scheduling
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Book consultations with certified financial experts and manage your calendar effortlessly.
                        </p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="flex flex-col items-center text-center">
                        <svg
                            class="h-10 w-10 text-gray-700 dark:text-gray-200 mb-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 1m0 0l-1 14m1-14L6.95 21" />
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-2">
                            Stay Informed
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Access real-time market news and financial updates, tailored to your unique goals and interests.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom CTA Section -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Take the Next Step in Your Financial Future
                </h2>
                <p class="text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                    Sign up for WealthWise and gain the tools to grow and protect your wealth, with expert guidance every step of the way.
                </p>
                <div>
                    <a href="{{ route('register') }}"
                       class="inline-block bg-gray-800 hover:bg-gray-900 text-white font-medium px-5 py-3 rounded transition">
                        Create an Account
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
