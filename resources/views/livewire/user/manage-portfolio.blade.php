<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Manage Portfolio') }}
    </h2>
</x-slot>
<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex justify-end items-center">
            <a href="{{ route('portfolio.add') }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                Add New Asset
            </a>
        </div>

    @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Asset Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Asset Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Value</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ROI (%)</th>
                    <th class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($portfolioItems as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $item->asset_type }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $item->asset_name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">${{ number_format($item->current_value, 2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $item->return_on_investment }}%</td>
                        <td class="px-4 py-2 text-right text-sm">
                            <button wire:click="openEditModal({{ $item->id }})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2">
                                Edit
                            </button>
                            <button wire:click="deleteAsset({{ $item->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-gray-500">No assets found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        @if ($showEditModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <h2 class="text-xl font-bold mb-4">Edit Asset</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Asset Type
                        </label>
                        <select wire:model="editingAssetType"
                                class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Asset Type</option>
                            <option value="Stock">Stock</option>
                            <option value="Bond">Bond</option>
                            <option value="ETF">ETF</option>
                            <option value="Real Estate">Real Estate</option>
                        </select>
                        @error('editingAssetType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Asset Name</label>
                        <input type="text" wire:model.defer="editingAssetName" class="form-control w-full">
                        @error('editingAssetName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>


                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" wire:model.defer="editingQuantity" class="form-control w-full">
                        @error('editingQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Value</label>
                        <input type="number" wire:model.defer="editingValue" class="form-control w-full">
                        @error('editingValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button wire:click="saveEdit"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Save
                        </button>
                        <button wire:click="closeEditModal"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
