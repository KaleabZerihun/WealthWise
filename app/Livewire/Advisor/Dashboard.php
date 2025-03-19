<?php

namespace App\Livewire\Advisor;

use App\Models\News;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

        $this->latestNews = News::orderBy('published_at','desc')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.advisor.dashboard')->layout('layouts.app');
    }
}
