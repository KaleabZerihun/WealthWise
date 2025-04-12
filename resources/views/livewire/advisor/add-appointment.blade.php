<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book an Appointment') }}
        </h2>
    </div>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('message'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4">New Appointment</h3>

            <form wire:submit.prevent="storeAppointment" class="space-y-5">

                <!-- 1) Appointment Day/Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Day/Time
                    </label>
                    <!-- When changed, we call fetchUsers to update the client list -->
                    <input wire:change="fetchUsers"
                           type="datetime-local"
                           wire:model="scheduledAt"
                           class="border border-gray-300 rounded w-full p-2" />
                    @error('scheduledAt')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">
                        The client list below updates once you pick a valid future time.
                    </p>
                </div>

                <!-- 2) Clients (Users) dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Available Clients
                    </label>
                    <select wire:model="selectedClient"
                            class="border border-gray-300 rounded w-full p-2">
                        <option value="">-- Pick a Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('selectedClient')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror

                    @if(empty($clients) && $scheduledAt)
                        <p class="text-xs text-red-500 mt-1">
                            No clients free at this time or time is in the past.
                        </p>
                    @endif
                </div>

                <!-- 3) Optional Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Note (Optional)
                    </label>
                    <textarea wire:model="note"
                              class="border border-gray-300 rounded w-full p-2"
                              rows="2"></textarea>
                    @error('note')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('advisor.appointments') }}"
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
