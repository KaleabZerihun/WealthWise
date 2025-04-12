<?php

namespace App\Livewire\Advisor;

use App\Models\Portfolio;
use App\Models\User;
use Livewire\Component;

class ClientPortfolioView extends Component
{
    public $clientId;
    public $clientName = 'Client';
    public $portfolioItems = [];
    public $totalValue = 0;
    public $assetTypeTotals = [
        'RealEstate' => 0,
        'Stocks'       => 0,
        'ETF'         => 0,
        'Bonds'        => 0
    ];

    public function mount($clientId)
    {
        $this->clientId = $clientId;



        $client = User::find($clientId);
        if ($client) {
            $this->clientName = $client->first_name . ' ' . $client->last_name;
        }

        $this->portfolioItems = Portfolio::where('user_id', $clientId)
            ->orderBy('asset_type','asc')
            ->get();

        foreach ($this->portfolioItems as $asset) {
            $value = $asset->investment_amount;
            $this->totalValue += $value;

            if (isset($this->assetTypeTotals[$asset->asset_type])) {
                $this->assetTypeTotals[$asset->asset_type] += $value;
            }
        }

        //$this->totalValue = $this->portfolioItems->sum('current_value');
    }

    public function render()
    {
        return view('livewire.advisor.client-portfolio-view')->layout('layouts.app');
    }
}
