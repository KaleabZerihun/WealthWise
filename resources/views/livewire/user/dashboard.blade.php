<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('User Dashboard') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Greeting & Intro -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Welcome, {{ auth()->user()->first_name ?? 'User' }}!
            </h1>
            <p class="text-gray-600 mt-1">
                Here’s an overview of your financial status and upcoming items.
            </p>
        </div>

        <!-- Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Portfolio Value Card -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-700">
                    Total Portfolio Value
                </h3>
                <div class="mt-2 text-3xl font-bold text-indigo-600">
                    ${{ number_format($portfolioValue, 2) }}
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Sum of your current portfolio’s total value.
                </p>
            </div>

            <!-- Upcoming Appointments Card -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    Upcoming Appointments
                </h3>
                @if ($upcomingAppointments->count() > 0)
                    <ul class="space-y-2">
                        @foreach ($upcomingAppointments as $appt)
                            <li class="border-b pb-1">
                                    <span class="text-gray-800">
                                        {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('M d, Y h:i A') }}
                                    </span>
                                <br>
                                <span class="text-sm text-gray-500">
                                        Status: {{ ucfirst($appt->status) }}
                                    </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No upcoming appointments.</p>
                @endif
                <a href="{{route('user.appointments')}}" class="inline-block mt-3 text-indigo-600 text-sm hover:underline">
                    View All Appointments
                </a>
            </div>

            <!-- News Highlights Card -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    News Highlights
                </h3>
                @if ($news->count() > 0)
                    <ul class="space-y-2">
                        @foreach ($news as $item)
                            <li class="border-b pb-1">
                                <p class="font-medium text-gray-800">
                                    {{ $item->title }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Published: {{ \Carbon\Carbon::parse($item->published_at)->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ Str::limit($item->content, 80) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No recent news at the moment.</p>
                @endif
                <a href="{{ route('user.news') }}" class="inline-block mt-3 text-indigo-600 text-sm hover:underline">
                    View All News
                </a>
            </div>
        </div>

        <!-- Financial Goals Section -->
        <div class="mt-6 bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                Your Financial Goals
            </h3>
            @if ($goals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($goals as $goal)
                        <div class="border rounded p-3">
                            <h4 class="font-medium text-gray-800">
                                {{ $goal->goal_type }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                Target: ${{ $goal->target_amount }} <br>
                                Current: ${{ $goal->current_amount }}
                            </p>
                                @php
                                    $displayStatus = ucwords(str_replace('_', ' ', $goal->goal_status));
                                    switch($goal->goal_status) {
                                        case 'in_progress':
                                            $colorClass = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'completed':
                                            $colorClass = 'bg-green-100 text-green-800';
                                            break;
                                        case 'on_hold':
                                            $colorClass = 'bg-red-100 text-red-800';
                                            break;
                                        default:
                                            $colorClass = 'bg-gray-100 text-gray-800';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ $displayStatus }}
                                </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven’t set any financial goals yet.</p>
            @endif
            <a href="{{ route('goals.manage') }}" class="inline-block mt-3 text-indigo-600 text-sm hover:underline">
                Manage Goals
            </a>
        </div>
    </div>
</div>
