<?php

namespace App\Livewire\Advisor;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class News extends Component
{
    public $news = [];

    public function mount()
    {
        $this->fetchNews();
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
        $newsItems = \App\Models\News::orderBy('published_at','desc')->get();
        return view('livewire.advisor.news')->layout('layouts.app');
    }
}
