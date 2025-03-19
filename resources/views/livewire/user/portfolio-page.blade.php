<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('My Portfolio') }}
    </h2>
</x-slot>


<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Title / Intro + Links -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ auth()->user()->first_name ?? 'User' }}!
                </h1>
                <p class="text-gray-600 mt-1">
                    Here’s a detailed view of your investment portfolio.
                </p>
            </div>
            <div class="space-x-2">
                <!-- Link to Manage Portfolio page -->
                <a href="{{ route('market') }}"
                   class="inline-block bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                    Buy New Asset
                </a>
            </div>
        </div>

        <!-- Total Portfolio Value Card -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-700">
                Total Portfolio Value
            </h3>
            <div class="mt-2 text-3xl font-bold text-indigo-600">
                ${{ number_format($totalValue, 2) }}
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Sum of all your current holdings' values.
            </p>
            <!-- Per-Asset-Type Totals -->
            <div class="mt-4">
                <h4 class="text-md font-semibold text-gray-700 mb-2">
                    By Asset Type
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Real Estate</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['RealEstate'], 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Stock</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['Stocks'], 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">ETF</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['ETF'], 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Bond</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['Bonds'], 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Items Table -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Asset Breakdown
            </h3>
            @if($portfolioItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Asset Type
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Asset Name
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Investment Amount
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bought Price
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Price
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Change ($)
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Change (%)
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sell
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($portfolioItems as $asset)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $asset->asset_type }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $asset->asset_name }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($asset->investment_amount, 2) }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($asset->bought_price, 2) }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $asset->quantity }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($asset->current_price, 2) }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-semibold
                                       {{ $asset->price_change > 0 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $asset->price_change > 0 ? '+' : '' }}${{ number_format($asset->price_change, 2) }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-semibold
                                       {{ $asset->percent_change > 0 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $asset->percent_change > 0 ? '+' : '' }}{{ number_format($asset->percent_change, 2) }}%
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-right">
                                    <button wire:click="openSellModal({{ $asset->id }}, {{ $asset->quantity }})"
                                            class="bg-red-600 hover:bg-red-500 text-white font-semibold py-1 px-3 rounded">
                                        Sell
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">You have no assets in your portfolio yet.</p>
            @endif
        </div>
        @if($showSellModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <h2 class="text-xl font-bold mb-4">Sell Asset</h2>

                    <!-- Fields -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Quantity to Sell</label>
                        <input type="number" wire:model="sellQuantity" min="1" max="{{ $maxSellQuantity }}"
                               class="form-control w-full border p-2 rounded">
                        @error('sellQuantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button wire:click="confirmSell"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Confirm Sell
                        </button>
                        <button wire:click="closeSellModal"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
