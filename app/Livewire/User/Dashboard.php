<?php

namespace App\Livewire\User;

use App\Models\News;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;
use App\Models\Appointment;
use App\Models\FinancialGoal;

class Dashboard extends Component
{
    public $portfolioValue = 0.00;
    public $upcomingAppointments = [];
    public $goals = [];
    public $news = [];

    // This method runs once the component is mounted/initialized
    public function mount()
    {
        // Usually, the logged-in user is a "Client"
        $client = Auth::guard('web')->user();
        // or "Auth::guard('client')->user();" if you have a separate guard

        if ($client) {
            // Sum up all 'current_value' for the user's portfolio
            $this->portfolioValue = Portfolio::where('user_id', $client->id)
                ->sum('current_value');

            // Fetch upcoming appointments (status 'pending')
            $this->upcomingAppointments = Appointment::where('user_id', $client->id)
                ->where('status', 'pending')
                ->orderBy('scheduled_at', 'asc')
                ->take(3)
                ->get();

            // Fetch the user's financial goals
            $this->goals = FinancialGoal::where('user_id', $client->id)
                ->get();
        }
        // Fetch some recent news articles (e.g. last 3)
        $this->news = News::orderBy('published_at', 'desc')
            ->take(3)
            ->get();
    }
    public function render()
    {
        return view('livewire.user.dashboard')->layout('layouts.app');
    }
}
