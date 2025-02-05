<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class Appointments extends Component
{
    public $appointments = [];

    public function mount()
    {
        $this->fetchAppointments();
    }

    public function fetchAppointments()
    {
        $user = Auth::user();
        if ($user) {
            $this->appointments = Appointment::where('user_id', $user->id)
                ->orderBy('scheduled_at','asc')
                ->get();
        }
    }

    public function cancelAppointment($id)
    {
        $appt = Appointment::findOrFail($id);

        $appt->delete();
        $this->fetchAppointments();

        session()->flash('message', 'Appointment canceled successfully.');
    }

    public function render()
    {
        return view('livewire.user.appointments')->layout('layouts.app');
    }
}
