<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create News Item') }}
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
            <h3 class="text-lg font-bold text-gray-700 mb-4">New News Item</h3>

            <form wire:submit.prevent="storeNews" class="space-y-4">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Title
                    </label>
                    <input type="text" wire:model="title"
                           class="border border-gray-300 rounded w-full p-2">
                    @error('title')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Content
                    </label>
                    <textarea wire:model="content"
                              rows="4"
                              class="border border-gray-300 rounded w-full p-2"></textarea>
                    @error('content')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Category (Optional)
                    </label>
                    <input type="text" wire:model="category"
                           class="border border-gray-300 rounded w-full p-2">
                    @error('category')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Published At (Optional)
                    </label>
                    <input type="datetime-local" wire:model="published_at"
                           class="border border-gray-300 rounded w-full p-2">
                    @error('published_at')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.news') }}"
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
