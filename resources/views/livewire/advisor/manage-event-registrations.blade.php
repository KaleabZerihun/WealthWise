<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Manage Event Registrations') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Manage Client Event Registrations
            </h1>
            <p class="text-gray-600 mt-1">
                Select a client and register/unregister them for events.
            </p>
        </div>

        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-100 text-green-800 p-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Select Client -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Select Client</h2>

            <select wire:model.live="selectedClientId"  class="w-full border-gray-300 rounded p-2">
                <option value="">-- Select a Client --</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->first_name }} {{ $client->last_name }} ({{ $client->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Events List -->
        @if ($selectedClientId)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Available Events</h2>

                <div class="space-y-6">
                    @foreach ($events as $event)
                        <div class="bg-gray-50 p-5 rounded flex flex-col md:flex-row md:justify-between md:items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }} |
                                    Speaker: {{ $event->speaker }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $event->location }}</p>
                            </div>

                            <div class="flex flex-col md:flex-row items-start md:items-center space-y-3 md:space-y-0 md:space-x-4 mt-4 md:mt-0">
                                @if (in_array($event->id, $registeredEventIds))
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Registered
                                    </span>

                                    <button wire:click="unregisterClient({{ $event->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                                        Unregister
                                    </button>
                                @else
                                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold">
                                        Not Registered
                                    </span>

                                    <button wire:click="registerClient({{ $event->id }})"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm">
                                        Register
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
