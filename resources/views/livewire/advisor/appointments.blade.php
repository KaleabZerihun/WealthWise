<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Appointments') }}
        </h2>
        <!-- Link to the "Add Appointment" page -->
        <a href="{{ route('advisor.appointments.add') }}"
           class="hidden sm:inline-block bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
            Book Appointment
        </a>
    </div>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('message'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <!-- Appointments Table -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Your Appointments</h3>

            @if($appointments->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Scheduled At</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($appointments as $appt)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $appt->user->first_name ?? 'Advisor' }}
                                {{ $appt->user->last_name ?? '' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ ucfirst($appt->status) }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $appt->notes ?? '--' }}
                            </td>
                            <td class="px-4 py-2 text-right text-sm">
                                <button wire:click="cancelAppointment({{ $appt->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm text-gray-500">No appointments found. Book a new one!</p>
            @endif
        </div>

    </div>
</div>
