<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Appointment;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    /* ---------- public props exposed to the view ---------- */
    public int $totalClients           = 0;
    public int $totalAdvisors          = 0;
    public int $totalAdmins            = 0;
    public int $pendingAppointments    = 0;
    public int $appointmentsToday      = 0;

    public $topAdvisors    = [];   // Eloquent collections
    public $pendingList    = [];
    public $unverifiedList = [];
    public $upcomingEvents = [];

    /* ---------- gather metrics on mount ---------- */
    public function mount(): void
    {
        /* basic counts */
        $this->totalClients  = User::where('user_type', 'user')->count();
        $this->totalAdvisors = Advisor::count();
        $this->totalAdmins   = Admin::count();

        /* appointment stats */
        $this->pendingAppointments = Appointment::where('status', 'pending')->count();
        $this->appointmentsToday = Appointment::whereDate(
            \Carbon\Carbon::now('America/Chicago'),
            'scheduled_at'
        )
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();


        /* top 5 advisors by completed appts */
        $this->topAdvisors = Advisor::withCount([
            'appointments as completed_appointments_count' => fn ($q) =>
            $q->where('status', 'completed'),
        ])
            ->orderByDesc('completed_appointments_count')
            ->take(5)
            ->get();

        /* newest pending appointments — now eager-loads client & advisor names */
        $this->pendingList = Appointment::with([
            'user:id,first_name,last_name',      // the client
            'advisor:id,first_name,last_name',   // the advisor
        ])
            ->where('status', 'pending')
            ->latest('scheduled_at')
            ->take(10)
            ->get();


        /* users who haven’t verified email after 7 days */
        $this->unverifiedList = User::whereNull('email_verified_at')
            ->whereDate('created_at', '<=', now()->subDays(7))
            ->orderBy('created_at')
            ->take(10)
            ->get();

        $this->upcomingEvents = Event::where('start_time', '>=', Carbon::now())
            ->orderBy('start_time', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}
