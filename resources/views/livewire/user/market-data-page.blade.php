<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Buy Asset') }}
    </h2>
</x-slot>

<div class="bg-gray-100 min-h-screen py-8 px-4">
    <!-- Container -->
    <div class="max-w-4xl mx-auto">

        <!-- Heading -->
        <h1 class="text-3xl font-bold mb-4">Live Market Data</h1>

        <!-- Flash message -->
        @if(session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg p-4">
        <!-- Asset Type & Refresh Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="mb-2 sm:mb-0">
                <label class="block text-gray-700 font-medium mb-1" for="assetType">
                    Asset Type
                </label>
                <select id="assetType" wire:model="assetType" wire:change="fetchAssets"
                        class="block w-48 bg-white border border-gray-300
                            rounded px-3 py-2 shadow-sm focus:outline-none
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Stocks">Stocks</option>
                    <option value="RealEstate">Real Estate</option>
                    <option value="Bonds">Bonds</option>
                    <option value="ETF">ETF</option>
                </select>

                <div class="mt-2">
                    Selected Asset: {{ $assetType }}
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Name</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Price</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Change</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Chg%</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Open</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">High</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Low</th>
                    <th class="px-4 py-3"></th> <!-- For Buy button -->
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-700">
                            {{ $asset['symbol'] }}
                        </td>
                        <td class="px-4 py-3">
                            {{ number_format($asset['current'], 2) }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $chg = number_format($asset['change'], 2);
                            @endphp

                            @if($asset['change'] > 0)
                                <span class="text-green-500">+{{ $chg }}</span>
                            @elseif($asset['change'] < 0)
                                <span class="text-red-500">{{ $chg }}</span>
                            @else
                                <span class="text-gray-600">{{ $chg }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $chgPct = number_format($asset['changePercent'], 2).'%';
                            @endphp

                            @if($asset['changePercent'] > 0)
                                <span class="text-green-500">+{{ $chgPct }}</span>
                            @elseif($asset['changePercent'] < 0)
                                <span class="text-red-500">{{ $chgPct }}</span>
                            @else
                                <span class="text-gray-600">{{ $chgPct }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{ number_format($asset['open'], 2) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ number_format($asset['high'], 2) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ number_format($asset['low'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="openBuyModal('{{ $asset['symbol'] }}', '{{ $asset['current'] }}')"
                                    class="bg-indigo-600 hover:bg-indigo-500
                                               text-white font-semibold py-1 px-3 rounded">
                                Buy
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                            No data available
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <h2 class="text-xl font-bold mb-4">Buy Asset</h2>

                    <!-- Fields -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Quantity
                        </label>
                        <input type="text" wire:model.defer="selectedSymbol"
                               class="form-control w-full border p-2 rounded">
                        @error('selectedSymbol')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Quantity
                        </label>
                        <input type="text" wire:model.defer="quantity"
                               class="form-control w-full border p-2 rounded">
                        @error('quantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button wire:click="buyAsset"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Buy
                        </button>
                        <button wire:click="closeModal"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
