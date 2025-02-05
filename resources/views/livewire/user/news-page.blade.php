    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Market News & Education Resources') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8">

            <!-- Intro Title -->
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-700 mb-2">Stay Up-to-Date</h1>
                <p class="text-md text-gray-600 max-w-xl mx-auto">
                    Browse the latest market news, and educational content.
                    Click "Read More" to view details.
                </p>
            </div>

            <!-- News Items Grid -->
            <div class="bg-white shadow-md rounded-lg p-6">
                @if($newsItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($newsItems as $item)
                            <div class="border rounded-lg p-4 flex flex-col">
                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $item->title }}
                                </h3>

                                <!-- Category & Date -->
                                <div class="text-sm text-gray-500 mt-1 flex justify-between">
                                    <span>
                                        {{ $item->category ? ucfirst($item->category) : 'General' }}
                                    </span>
                                    @if($item->published_at)
                                        <span>
                                            {{ \Carbon\Carbon::parse($item->published_at)->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Content snippet -->
                                <p class="mt-2 text-gray-700 text-sm">
                                    {{ Str::limit($item->content, 100) }}
                                </p>

                                <!-- Read More button triggers the modal -->
                                <button wire:click="openReadMoreModal({{ $item->id }})"
                                        type="button"
                                        class="mt-auto inline-block bg-indigo-600 text-white py-2 px-3 rounded hover:bg-indigo-700 text-sm text-center">
                                    Read More
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">
                        No news found.
                    </p>
                @endif
            </div>
            <!-- Read More Modal -->
            @if($showReadMoreModal && $selectedItem)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white max-w-xl w-full p-6 rounded shadow-lg">
                        <h2 class="text-xl font-bold mb-2">
                            {{ $selectedItem->title }}
                        </h2>
                        <div class="text-sm text-gray-500 mb-3 flex justify-between">
                    <span>
                        {{ $selectedItem->category ? ucfirst($selectedItem->category) : 'General' }}
                    </span>
                            @if($selectedItem->published_at)
                                <span>
                            {{ \Carbon\Carbon::parse($selectedItem->published_at)->format('M d, Y h:i A') }}
                        </span>
                            @endif
                        </div>
                        <div class="mb-4 text-gray-700">
                            {{ $selectedItem->content }}
                        </div>

                        <!-- Close Button -->
                        <div class="flex justify-end">
                            <button wire:click="closeReadMoreModal"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


