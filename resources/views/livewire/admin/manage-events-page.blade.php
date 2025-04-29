<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Manage Events') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Manage Events & Workshops
            </h1>
            <p class="text-gray-600 mt-1">
                Create, update, and manage financial education events.
            </p>
        </div>

        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-100 text-green-800 p-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Create/Edit Event Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                {{ $eventId ? 'Edit Event' : 'Create New Event' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" wire:model.defer="title" class="w-full border-gray-300 rounded p-2" />
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea wire:model.defer="description" class="w-full border-gray-300 rounded p-2"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                        <input type="datetime-local" wire:model.defer="start_time" class="w-full border-gray-300 rounded p-2" />
                        @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Speaker</label>
                        <input type="text" wire:model.defer="speaker" class="w-full border-gray-300 rounded p-2" />
                        @error('speaker') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" wire:model.defer="location" class="w-full border-gray-300 rounded p-2" />
                    @error('location') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="flex space-x-4 pt-4">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                        {{ $eventId ? 'Update Event' : 'Create Event' }}
                    </button>
                    @if ($eventId)
                        <button type="button" wire:click="resetForm"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Existing Events List -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Existing Events</h2>

        <div class="space-y-6">
            @forelse ($events as $event)
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }} |
                            Speaker: {{ $event->speaker }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $event->location }}</p>
                    </div>

                    <div class="flex space-x-3 mt-4 md:mt-0">
                        <button wire:click="edit({{ $event->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                            Edit
                        </button>

                        <button wire:click="delete({{ $event->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No events found.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>

    </div>
</div>
