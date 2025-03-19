<?php

namespace App\Livewire\User;

use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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

    public function mount()
    {
        $client = Auth::guard('web')->user();

        if ($client) {
            $this->portfolioValue = Portfolio::where('user_id', $client->id)
                ->sum('investment_amount');

            // Fetch upcoming appointments
            $this->upcomingAppointments = Appointment::where('user_id', $client->id)
                ->where('status', 'pending')
                ->orderBy('scheduled_at', 'asc')
                ->take(3)
                ->get();

            // Fetch the user's financial goals
            $this->goals = FinancialGoal::where('user_id', $client->id)
                ->get();
        }
        // Fetch news highlights
        $cacheKey = 'market_news';
        if (Cache::has($cacheKey)) {
            $this->news = array_slice(Cache::get($cacheKey), 0, 3);
        } else {
            $this->fetchNews();
        }
    }

    public function fetchNews()
    {
        $apiKey = 'cvdk961r01qm9khlfmu0cvdk961r01qm9khlfmug';
        $url = "https://finnhub.io/api/v1/news?category=general&token={$apiKey}";

        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json();

            // Filter out MarketWatch articles and those without images
            $filteredNews = array_filter($articles, function ($article) {
                return
                    !str_contains(strtolower($article['source'] ?? ''), 'marketwatch') &&
                    !empty($article['image']);
            });

            $this->news = array_values($filteredNews); // Reset array keys
        } else {
            $this->news = [];
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard')->layout('layouts.app');
    }
}
