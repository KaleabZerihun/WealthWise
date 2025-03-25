<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class PortfolioPage extends Component
{
    public $portfolioItems = [];
    public $totalValue = 0;

    public $assetTypeTotals = [
        'RealEstate' => 0,
        'Stocks'       => 0,
        'ETF'         => 0,
        'Bonds'        => 0
    ];
    public $showSellModal = false;
    public $sellQuantity;
    public $maxSellQuantity;
    public $assetIdToSell;
    private $apiKey = 'cvdk961r01qm9khlfmu0cvdk961r01qm9khlfmug';

    public function mount()
    {
        $client = Auth::user();
        if ($client) {

            $this->portfolioItems = Portfolio::where('user_id', $client->id)->get();
            $this->calculateTotals();
            //fetch current price in parallel
            $this->fetchCurrentPrices();
        }
    }

    private function fetchCurrentPrices()
    {
        foreach ($this->portfolioItems as $asset) {
            $apiResponse = Http::get('https://finnhub.io/api/v1/quote', [
                'symbol' => $asset->asset_name,
                'token'  => $this->apiKey,
            ]);

            if ($apiResponse->successful()) {
                $data = $apiResponse->json();
                $currentPrice = $data['c'] ?? 0;
                $boughtPrice = $asset->investment_amount / max($asset->quantity, 1);

                // Calculate price change and percentage change
                $priceChange = $currentPrice - $boughtPrice;
                $percentChange = ($boughtPrice > 0) ? ($priceChange / $boughtPrice) * 100 : 0;

                // Add calculated values to asset object
                $asset->current_price = $currentPrice;
                $asset->price_change = $priceChange;
                $asset->percent_change = $percentChange;
                $asset->bought_price = $boughtPrice;
            }
        }
    }
    private function calculateTotals()
    {
        foreach ($this->portfolioItems as $asset) {
            $value = $asset->investment_amount;
            $this->totalValue += $value;

            if (isset($this->assetTypeTotals[$asset->asset_type])) {
                $this->assetTypeTotals[$asset->asset_type] += $value;
            }
        }
    }

    public function openSellModal($assetId, $maxQuantity)
    {
        $this->assetIdToSell = $assetId;
        $this->maxSellQuantity = $maxQuantity;
        $this->sellQuantity = 1;
        $this->showSellModal = true;
    }

    public function closeSellModal()
    {
        $this->showSellModal = false;
        $this->sellQuantity = 1;
    }

    public function confirmSell()
    {
        if (!$this->assetIdToSell || $this->sellQuantity < 1) {
            session()->flash('error', 'Invalid quantity');
            return;
        }

        $asset = Portfolio::find($this->assetIdToSell);

        if (!$asset || $this->sellQuantity > $asset->quantity) {
            session()->flash('error', 'Invalid sell amount');
            return;
        }

        if ($this->sellQuantity == $asset->quantity) {
            $asset->delete();
        } else {
            // Adjust quantity and investment amount proportionally
            $newQuantity = $asset->quantity - $this->sellQuantity;
            $newInvestmentAmount = ($asset->investment_amount / $asset->quantity) * $newQuantity;

            $asset->quantity = $newQuantity;
            $asset->investment_amount = $newInvestmentAmount;
            $asset->save();
        }
        // Recalculate portfolio totals
        $this->mount();

        session()->flash('message', 'Asset sold successfully!');
        return redirect()->route('portfolio');
    }

    public function render()
    {
        return view('livewire.user.portfolio-page')->layout('layouts.app');
    }
}
