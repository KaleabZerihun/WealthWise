<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Advisor Dashboard') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Message -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Hello, {{ $advisorName ?? 'Advisor' }}!
            </h1>
            <p class="text-gray-600 mt-1">
                Here’s an overview of your appointments, market insights, portfolios, and events.
            </p>
        </div>

        <!-- ✅ All content wrapped in one root element -->
        <div class="space-y-10">

            <!-- Top Row: Appointments, News, Portfolios -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Appointments -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Appointments</h3>

                    @if(!empty($upcomingAppointments) && $upcomingAppointments->count() > 0)
                        <ul class="space-y-3 flex-1">
                            @foreach($upcomingAppointments->take(3) as $appt)
                                <li class="border-b pb-2">
                                    <div class="text-gray-800">
                                        {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Status: {{ ucfirst($appt->status) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('advisor.appointments') }}"
                           class="mt-4 inline-block text-indigo-600 text-sm hover:underline self-end">
                            View All Appointments
                        </a>
                    @else
                        <p class="text-gray-500 flex-1 mt-4">No upcoming appointments.</p>
                    @endif
                </div>

                <!-- News -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Market News Highlights</h3>

                    @if (count($news) > 0)
                        @foreach (array_slice($news, 0, 2) as $item)
                            <div class="border-b pb-2 mb-2">
                                <h4 class="text-md font-semibold text-gray-800">{{ $item['headline'] }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item['datetime'])->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-700 mt-1">{{ Str::limit($item['summary'], 80) }}</p>
                                <a href="{{ $item['url'] }}" target="_blank"
                                   class="text-blue-600 text-sm hover:underline font-semibold">Read More →</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No recent market news.</p>
                    @endif

                    <a href="{{ route('user.news') }}"
                       class="mt-4 inline-block text-indigo-600 text-sm hover:underline self-end">
                        View All News
                    </a>
                </div>

                <!-- Portfolios -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Client Portfolio Values</h3>

                    @if(!empty($clientPortfolioSums) && count($clientPortfolioSums) > 0)
                        <div class="overflow-x-auto flex-1">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                        Client Name
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                        Total Value
                                    </th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($clientPortfolioSums as $client)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $client['user_name'] }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            ${{ number_format($client['total_value'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-right text-sm">
                                            <a href="{{ route('advisor.clientPortfolio', ['clientId' => $client['user_id']]) }}"
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                                                Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">
                            No portfolio records found.
                        </p>
                    @endif
                </div>

            </div>

            <!-- Bottom Row: Events Section -->
            <div class="grid grid-cols-1">
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Events</h3>

                    @if(!empty($upcomingEvents) && $upcomingEvents->count() > 0)
                        <ul class="space-y-3 flex-1">
                            @foreach($upcomingEvents->take(5) as $event)
                                <li class="border-b pb-2">
                                    <div class="text-gray-800 font-semibold">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('advisor.manage-events') }}"
                           class="mt-4 inline-block text-indigo-600 text-sm hover:underline self-end">
                            View All Events
                        </a>
                    @else
                        <p class="text-gray-500 flex-1 mt-4">No upcoming events.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
