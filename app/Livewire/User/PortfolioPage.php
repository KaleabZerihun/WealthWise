<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;

class PortfolioPage extends Component
{
    public $portfolioItems = [];
    public $totalValue = 0;

    // We'll store totals for these four types in an array
    public $assetTypeTotals = [
        'Real Estate' => 0,
        'Stock'       => 0,
        'ETF'         => 0,
        'Bond'        => 0
    ];

    public function mount()
    {
        $client = Auth::user();
        if ($client) {
            // 1) Fetch the user's portfolio items
            $this->portfolioItems = Portfolio::where('user_id', $client->id)->get();

            // 2) Calculate the overall total
            $this->totalValue = 0;

            // 3) Build per-type sums
            foreach ($this->portfolioItems as $asset) {
                $value = $asset->current_value; // Or compute if needed
                $this->totalValue += $value;

                // If asset type matches one of the four known types, add to its sum
                if (isset($this->assetTypeTotals[$asset->asset_type])) {
                    $this->assetTypeTotals[$asset->asset_type] += $value;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.user.portfolio-page')->layout('layouts.app');
    }
}
