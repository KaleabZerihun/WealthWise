<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Add New Asset') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen" >
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Message -->
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Enter Asset Details
            </h1>

            <form wire:submit.prevent="store" class="space-y-4">
                <!-- Asset Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Asset Type
                    </label>
                    <select wire:model="asset_type"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Select Asset Type</option>
                        <option value="Stock">Stock</option>
                        <option value="Bond">Bond</option>
                        <option value="ETF">ETF</option>
                        <option value="Real Estate">Real Estate</option>
                    </select>
                    @error('asset_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Asset -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Asset Name
                    </label>
                    <input wire:model="asset_name" step="any"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. Apple, Amazon">
                    @error('asset_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Investment Amount Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Investment Amount
                    </label>
                    <input type="number" wire:model="investment_amount" step="any"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. 1500.00">
                    @error('investment_amount')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Quantity
                    </label>
                    <input type="number" wire:model="quantity" step="any"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. 10">
                    @error('quantity')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purchase Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Purchase Date (Optional)
                    </label>
                    <input type="date" wire:model="purchase_date"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @error('purchase_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Current Value
                    </label>
                    <input type="number" wire:model="current_value" step="any"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="e.g. 2500.00">
                    @error('current_value')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Risk Score -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Risk Score (Optional)
                    </label>
                    <input type="number" wire:model="risk_score" step="any"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           placeholder="Between 0-10?">
                    @error('risk_score')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('portfolio.manage') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
