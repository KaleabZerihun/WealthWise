<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;
class PortfolioPage extends Component
{
    public $portfolioItems = [];
    public $totalValue = 0;

    public function mount()
    {
        $client = Auth::user();
        if ($client) {
            // Fetch the user's portfolio items
            $this->portfolioItems = Portfolio::where('user_id', $client->id)
                ->get();

            // Calculate total portfolio value
            $this->totalValue = $this->portfolioItems->sum('current_value');
        }
    }
    public function render()
    {
        return view('livewire.user.portfolio-page')->layout('layouts.app');
    }
}
