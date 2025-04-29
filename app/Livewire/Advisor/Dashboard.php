<?php

namespace App\Livewire\Advisor;

use App\Models\Event;
use App\Models\News;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $clientPortfolioSums = [];
    public $grandTotalPortfolio = 0;
    public $advisorName = 'Advisor';
    public $upcomingAppointments = [];
    public $news = [];
    public $upcomingEvents = [];
    public $latestNews = [];


    public function mount()
    {
        $advisor = Auth::guard('advisor')->user();
        if ($advisor) {
            $this->advisorName = $advisor->first_name ?? 'Advisor';
        }


        $result = Portfolio::select('user_id', DB::raw('SUM(investment_amount) as total_value'))
            ->groupBy('user_id')
            ->get();


        $this->clientPortfolioSums = $result->map(function($row){
            $user = User::find($row->user_id);
            return [
                'user_id'    => $row->user_id,
                'user_name'  => $user ? ($user->first_name ?? 'User '.$user->id) : 'User '.$row->user_id,
                'total_value'=> $row->total_value,
            ];
        })->toArray();

        $this->upcomingAppointments = Appointment::where('advisor_id', Auth::id())
            ->where('scheduled_at','>=', now())
            ->orderBy('scheduled_at','asc')
            ->take(3)
            ->get();

        $this->upcomingEvents = Event::where('start_time', '>=', Carbon::now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

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
        return view('livewire.advisor.dashboard')->layout('layouts.app');
    }
}
