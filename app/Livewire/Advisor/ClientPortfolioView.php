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
        'Real Estate' => 0,
        'Stock'       => 0,
        'ETF'         => 0,
        'Bond'        => 0
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
            $value = $asset->current_value; // Or compute if needed
            $this->totalValue += $value;

            // If asset type matches one of the four known types, add to its sum
            if (isset($this->assetTypeTotals[$asset->asset_type])) {
                $this->assetTypeTotals[$asset->asset_type] += $value;
            }
        }

        $this->totalValue = $this->portfolioItems->sum('current_value');
    }

    public function render()
    {
        return view('livewire.advisor.client-portfolio-view')->layout('layouts.app');
    }
}
