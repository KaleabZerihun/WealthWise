<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

        {{-- ────────── KPI CARDS ────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Clients, Advisors, Admins, Pending Appointments Cards -->
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500">Total Clients</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalClients }}</p>
            </div>

            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500">Total Advisors</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalAdvisors }}</p>
            </div>

            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500">Total Admins</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalAdmins }}</p>
            </div>

            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500">Pending Appointments</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $pendingAppointments }}</p>
            </div>
        </div>

        {{-- ────────── TODAY’S APPOINTMENTS COUNT ────────── --}}
        <div class="bg-white rounded shadow p-6">
            <p class="text-lg font-semibold text-gray-800">
                Appointments Today: <span class="font-bold">{{ $appointmentsToday }}</span>
            </p>
        </div>



        {{-- ────────── PENDING APPOINTMENTS TABLE ────────── --}}
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Pending Appointments</h3>

            @if($pendingList->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date / Time</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Advisor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingList as $appt)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $appt->user?->first_name }} {{ $appt->user?->last_name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $appt->advisor?->first_name }} {{ $appt->advisor?->last_name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700 capitalize">
                                    {{ $appt->status }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No pending appointments.</p>
            @endif
        </div>

        {{-- ────────── UPCOMING EVENTS SECTION ────────── --}}
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Upcoming Events</h3>

            @if($upcomingEvents->count())
                <ul class="divide-y divide-gray-200">
                    @foreach($upcomingEvents->take(5) as $event)
                        <li class="py-2">
                            <div class="font-semibold text-gray-800">{{ $event->title }}</div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('admin.manage-events') }}"
                   class="mt-4 inline-block text-indigo-600 text-sm hover:underline">
                    View All Events
                </a>
            @else
                <p class="text-gray-500">No upcoming events scheduled.</p>
            @endif
        </div>



    </div>
</div>
