<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('My Portfolio') }}
    </h2>
</x-slot>

<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Title / Intro + Manage Link -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ auth()->user()->first_name ?? 'User' }}!
                </h1>
                <p class="text-gray-600 mt-1">
                    Here’s a detailed view of your investment portfolio.
                </p>
            </div>
            <!-- Link to ManagePortfolio page -->
            <a href="{{ route('portfolio.manage') }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                Manage Portfolio
            </a>
        </div>

        <!-- Total Portfolio Value Card -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-700">
                Total Portfolio Value
            </h3>
            <div class="mt-2 text-3xl font-bold text-indigo-600">
                <!-- to add comma and to do 2 decimal place -->
                ${{ number_format($totalValue, 2) }}
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Sum of all your current holdings' values.
            </p>
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
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Asset Type
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Value
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ROI (%)
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($portfolioItems as $asset)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $asset->asset_type }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $asset->quantity }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($asset->current_value, 2) }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $asset->return_on_investment }}%
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
    </div>
</div>
