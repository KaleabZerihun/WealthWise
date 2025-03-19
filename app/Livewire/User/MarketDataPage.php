<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;

class MarketDataPage extends Component
{
    public $assets = [];
    public $assetType = 'Stocks';
    public $risk_score;

    public $showModal = false;
    public $selectedSymbol = null;
    public $current = null;
    public $quantity = 1;

    private $apiKey = 'cvdk961r01qm9khlfmu0cvdk961r01qm9khlfmug';

    public function mount()
    {
        $this->fetchAssets();
    }

    public function fetchAssets()
    {
        try {
            // Define asset categories
            $assetCategories = [
                'Stocks' => ['AAPL', 'MSFT', 'TSLA', 'AMZN', 'NFLX', 'GOOGL', 'NVDA', 'META', 'JPM', 'V', 'MA'],
                'RealEstate' => ['O', 'AMT', 'PLD', 'SPG', 'EQIX', 'WELL', 'VTR', 'EQR', 'AVB', 'PSA'],
                'ETF' => ['SPY', 'QQQ', 'DIA', 'IWM', 'VTI', 'VOO', 'IVV', 'VUG', 'XLK', 'GLD'],
                'Bonds' => ['AGG', 'BND', 'LQD', 'HYG', 'TLT', 'IEI', 'IEF', 'TIP', 'SHY', 'BSV']
            ];

            $symbols = $assetCategories[$this->assetType] ?? $assetCategories['Stocks'];

            // Check cache first
            $cacheKey = 'market_data_' . $this->assetType;
            if (Cache::has($cacheKey)) {
                $this->assets = Cache::get($cacheKey);
                return;
            }

            // Use Guzzle to fetch multiple API requests in parallel
            $client = new Client();
            $promises = [];

            foreach ($symbols as $symbol) {
                $promises[$symbol] = $client->getAsync("https://finnhub.io/api/v1/quote", [
                    'query' => [
                        'symbol' => $symbol,
                        'token' => $this->apiKey,
                    ]
                ]);
            }

            $responses = Promise\Utils::settle($promises)->wait();


            $fetched = [];

            foreach ($responses as $symbol => $response) {
                if ($response['state'] === 'fulfilled' && $response['value']->getStatusCode() === 200) {
                    $data = json_decode($response['value']->getBody(), true);

                    $current = $data['c'] ?? null;
                    $previous = $data['pc'] ?? null;
                    $change = ($current && $previous) ? $current - $previous : null;
                    $changePercent = ($change && $previous)
                        ? round(($change / $previous) * 100, 2)
                        : null;

                    $fetched[] = [
                        'symbol' => $symbol,
                        'current' => $current,
                        'open' => $data['o'] ?? null,
                        'high' => $data['h'] ?? null,
                        'low' => $data['l'] ?? null,
                        'prevClose' => $previous,
                        'change' => $change,
                        'changePercent' => $changePercent,
                    ];
                }
            }

            $this->assets = $fetched;

            // Cache results for 10 minutes
            Cache::put($cacheKey, $fetched, now()->addMinutes(2));

        } catch (\Exception $e) {
            $this->assets = [];
        }
    }

    public function openBuyModal($symbol, $current)
    {
        $this->current = $current;
        $this->selectedSymbol = $symbol;
        $this->quantity = 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSymbol = null;
        $this->quantity = 1;
    }

    public function buyAsset()
    {
        if (!$this->selectedSymbol || $this->quantity < 1) {
            session()->flash('error', 'Invalid symbol or quantity');
            return;
        }

        $this->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $client = Auth::user();
        if (!$client) {
            return;
        }

        $invest_amount = $this->current * $this->quantity;

        Portfolio::create([
            'user_id' => $client->id,
            'asset_type' => $this->assetType,
            'asset_name' => $this->selectedSymbol,
            'investment_amount' => $invest_amount,
            'quantity' => $this->quantity,
            'purchase_date' => now(),
            'current_value' => $this->current,
            'return_on_investment' => 0,
        ]);

        session()->flash('message', 'Asset purchased successfully!');
        return redirect()->route('portfolio');
    }

    public function render()
    {
        return view('livewire.user.market-data-page')->layout('layouts.app');
    }
}
