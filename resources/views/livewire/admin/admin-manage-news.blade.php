    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage News / Events') }}
            </h2>
            <!-- Link to "Add News" page -->
            <a href="{{ route('admin.news.add') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                + Create News
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success message -->
            @if(session('message'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-bold text-gray-700 mb-4">All News Items</h3>

                @if($newsItems->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    Title
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    Category
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    Published At
                                </th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($newsItems as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ $item->title }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ $item->category ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        @if($item->published_at)
                                            {{ \Carbon\Carbon::parse($item->published_at)->format('M d, Y h:i A') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right text-sm space-x-2">
                                        <button wire:click="openEditModal({{ $item->id }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                                            Edit
                                        </button>
                                        <button wire:click="deleteNews({{ $item->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No news items found.</p>
                @endif
            </div>
            <!-- Edit News Modal -->
            @if($showEditModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white w-full max-w-md mx-auto p-6 rounded shadow-lg">
                        <h2 class="text-xl font-bold mb-4">Edit News</h2>
                        <form wire:submit.prevent="updateNews" class="space-y-4">

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" wire:model="editTitle"
                                       class="border border-gray-300 rounded w-full p-2">
                                @error('editTitle')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                <textarea wire:model="editContent" rows="4"
                                          class="border border-gray-300 rounded w-full p-2"></textarea>
                                @error('editContent')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category (Optional)</label>
                                <input type="text" wire:model="editCategory"
                                       class="border border-gray-300 rounded w-full p-2">
                                @error('editCategory')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Published At -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Published At (Optional)</label>
                                <input type="datetime-local" wire:model="editPublishedAt"
                                       class="border border-gray-300 rounded w-full p-2">
                                @error('editPublishedAt')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-3">
                                <button type="button"
                                        wire:click="$set('showEditModal', false)"
                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>


