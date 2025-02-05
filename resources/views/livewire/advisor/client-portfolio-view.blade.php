<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Client Portfolio for ') . $clientName }}
        </h2>
    </div>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Portfolio Summary Card -->
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold text-gray-800">Total Portfolio Value</h3>
            <p class="mt-2 text-2xl font-extrabold text-indigo-600">
                ${{ number_format($totalValue, 2) }}
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Sum of all assets for {{ $clientName }}.
            </p>

            <div class="mt-4">
                <h4 class="text-md font-semibold text-gray-700 mb-2">
                    By Asset Type
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Real Estate -->
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Real Estate</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['Real Estate'], 2) }}
                        </p>
                    </div>
                    <!-- Stock -->
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Stock</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['Stock'], 2) }}
                        </p>
                    </div>
                    <!-- ETF -->
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">ETF</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['ETF'], 2) }}
                        </p>
                    </div>
                    <!-- Bond -->
                    <div class="bg-gray-50 p-3 rounded shadow">
                        <p class="text-sm font-medium text-gray-500">Bond</p>
                        <p class="text-lg font-bold text-gray-800">
                            ${{ number_format($assetTypeTotals['Bond'], 2) }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Table of Portfolio Items -->
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Asset Breakdown</h3>
            @if($portfolioItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Asset Type
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Asset Name
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Quantity
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Current Value
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                ROI (%)
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($portfolioItems as $asset)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $asset->asset_type }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $asset->asset_name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $asset->quantity }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    ${{ number_format($asset->current_value, 2) }}
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $asset->return_on_investment ?? 'N/A' }}%
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">
                    No portfolio items found for this client.
                </p>
            @endif
        </div>
    </div>
</div>
