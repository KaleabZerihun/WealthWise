<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Advisor Dashboard') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Hello, {{ $advisorName ?? 'Advisor' }}!
            </h1>
            <p class="text-gray-600 mt-1">
                Here’s an overview of your appointments, latest news,
                and client portfolio values.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white shadow rounded p-6 flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h3>
                @if(!empty($upcomingAppointments) && $upcomingAppointments->count() > 0)
                    <ul class="mt-2 space-y-2 flex-1">
                        @foreach($upcomingAppointments->take(3) as $appt)
                            <li class="text-gray-700 border-b pb-1">
                                <div>
                                    {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('M d, Y h:i A') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Status: {{ ucfirst($appt->status) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <a href="#"
                       class="mt-3 inline-block text-indigo-600 text-sm hover:underline self-end">
                        View All Appointments
                    </a>
                @else
                    <p class="text-gray-500 mt-2 flex-1">
                        No upcoming appointments.
                    </p>
                @endif
            </div>

            <div class="bg-white shadow rounded p-6 flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800">Latest News</h3>
                @if(!empty($latestNews) && $latestNews->count() > 0)
                    <ul class="mt-2 space-y-2 flex-1">
                        @foreach($latestNews->take(3) as $newsItem)
                            <li class="text-gray-700 border-b pb-1">
                                <div class="font-medium">
                                    {{ Str::limit($newsItem->title, 20) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Published:
                                    {{ \Carbon\Carbon::parse($newsItem->published_at)->format('M d, Y') }}
                                </div>
                                <p class="text-sm text-gray-600">
                                    {{ Str::limit($newsItem->content, 30) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                    <a href="#"
                       class="mt-3 inline-block text-indigo-600 text-sm hover:underline self-end">
                        View All News
                    </a>
                @else
                    <p class="text-gray-500 mt-2 flex-1">
                        No recent news.
                    </p>
                @endif
            </div>

            <div class="bg-white shadow rounded p-6 flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Client Portfolio Values
                </h3>
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

    </div>
</div>
