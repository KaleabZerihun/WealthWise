<?php

namespace App\Livewire\User;

use App\Models\Advisor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;

class AddAppointment extends Component
{
    public $scheduledAt = '';       // The user’s chosen date/time
    public $advisors = [];          // Advisors free at that time
    public $selectedAdvisor = '';   // The advisor user picks
    public $note = '';              // optional note

    public function mount()
    {
        $this->advisors = [];
    }


    // Find advisors NOT booked at $scheduledAt
    public function fetchAdvisors()
    {

        // parse chosen time
        if (!$this->scheduledAt) {
            $this->advisors = [];
            return;
        }

        $requestedTime = Carbon::parse($this->scheduledAt);

        // if time is in past, no advisors
        if ($requestedTime->isPast()) {
            $this->advisors = [];
            return;
        }

        // find advisors who are booked at $requestedTime
        $bookedIds = Appointment::where('scheduled_at', $requestedTime)->pluck('advisor_id');

        // exclude them from the list
        $this->advisors = Advisor::whereNotIn('id', $bookedIds)
            ->orderBy('first_name','asc')
            ->orderBy('last_name','asc')
            ->get();
    }

    public function storeAppointment()
    {
        $this->validate([
            'scheduledAt' => 'required|date',
            'selectedAdvisor' => 'required|integer',
            'note' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $requestedTime = Carbon::parse($this->scheduledAt);
        if ($requestedTime->isPast()) {
            $this->addError('scheduledAt','Cannot schedule an appointment in the past.');
            return;
        }

        // final concurrency check
        $alreadyBooked = Appointment::where('advisor_id', $this->selectedAdvisor)
            ->where('scheduled_at', $requestedTime)
            ->exists();
        if ($alreadyBooked) {
            $this->addError('selectedAdvisor','This advisor is already booked at this time. Please pick another time or advisor.');
            return;
        }

        // create new record
        Appointment::create([
            'user_id'      => $user->id,
            'advisor_id'   => $this->selectedAdvisor,
            'scheduled_at' => $requestedTime,
            'notes'        => $this->note,
            'status'       => 'pending',
        ]);

        session()->flash('message','Appointment booked successfully!');
        return redirect()->route('user.appointments');
    }


    public function render()
    {
        return view('livewire.user.add-appointment')->layout('layouts.app');
    }
}
