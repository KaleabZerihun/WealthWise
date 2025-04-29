<div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8">Events & Workshops</h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (!empty($myRegistrations))
        <div class="mb-8 p-6 bg-white shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">My Registered Events</h2>
            <ul class="list-disc pl-6">
                @foreach ($events->whereIn('id', $myRegistrations) as $event)
                    <li class="mb-2">
                        {{ $event->title }} ({{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y, g:i A') }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-8">
        @forelse ($events as $event)
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-2xl font-semibold">{{ $event->title }}</h2>
                <p class="text-gray-600 mb-2">{{ $event->description }}</p>
                <p class="text-sm text-gray-500">
                    <strong>Date/Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y, g:i A') }}
                </p>
                <p class="text-sm text-gray-500">
                    <strong>Speaker:</strong> {{ $event->speaker }}
                </p>
                <p class="text-sm text-gray-500">
                    <strong>Location:</strong> {{ $event->location }}
                </p>

                @if (in_array($event->id, $myRegistrations))
                    <button wire:click="unregister({{ $event->id }})" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">
                        Unregister
                    </button>
                @else
                    <button wire:click="register({{ $event->id }})" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                        Register
                    </button>
                @endif

            </div>
        @empty
            <p>No upcoming events at the moment.</p>
        @endforelse
    </div>
</div>
