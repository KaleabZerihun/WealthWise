<?php

namespace App\Livewire\Advisor;

use App\Models\Advisor;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddAppointment extends Component
{
    public $scheduledAt = '';    // The chosen date/time
    public $clients = [];        // The list of clients free at that time
    public $selectedClient = ''; // Which client is selected
    public $note = '';           // Optional note

    public function mount()
    {
        // Initially no clients are loaded
        $this->clients = [];
    }

    /**
     * When the user picks a day/time, we fetch which "clients" are free at that exact time,
     * meaning they do NOT have an appointment at that same scheduled time.
     */
    public function fetchUsers()
    {
        // If there's no chosen time, clear the list
        if (!$this->scheduledAt) {
            $this->clients = [];
            return;
        }

        // Parse chosen time into a Carbon instance
        $requestedTime = Carbon::parse($this->scheduledAt);

        // If time is in the past, show no clients
        if ($requestedTime->isPast()) {
            $this->clients = [];
            return;
        }

        // Find all user_ids that are booked at this exact time
        // For your logic: If a user has an appointment at that time, we exclude them.
        $bookedUserIds = Appointment::where('scheduled_at', $requestedTime)
            ->pluck('user_id');

        // List all users NOT in those booked IDs
        $this->clients = User::whereNotIn('id', $bookedUserIds)
            ->orderBy('first_name','asc')
            ->orderBy('last_name','asc')
            ->get();
    }

    /**
     * Store the new appointment:
     * - The current logged-in advisor is stored as "advisor_id"
     * - The selected client is stored as "user_id"
     */
    public function storeAppointment()
    {
        // Validate form input
        $this->validate([
            'scheduledAt'     => 'required|date',
            'selectedClient'  => 'required|integer',
            'note'            => 'nullable|string|max:500',
        ]);

        // Identify the currently logged-in advisor
        $advisor = Auth::guard('advisor')->user();
        if (!$advisor) {
            // Possibly redirect or throw an error
            return;
        }

        // Convert string to Carbon
        $requestedTime = Carbon::parse($this->scheduledAt);

        // Disallow scheduling in the past
        if ($requestedTime->isPast()) {
            $this->addError('scheduledAt','Cannot schedule an appointment in the past.');
            return;
        }

        // Check if the selected client is already booked at this exact time
        $alreadyBooked = Appointment::where('user_id', $this->selectedClient)
            ->where('scheduled_at', $requestedTime)
            ->exists();
        if ($alreadyBooked) {
            $this->addError('selectedClient','This client is already booked at this time. Please pick another time or client.');
            return;
        }

        // Create the new appointment record
        Appointment::create([
            'user_id'      => $this->selectedClient,  // the chosen user from dropdown
            'advisor_id'   => $advisor->id,           // the logged-in advisor
            'scheduled_at' => $requestedTime,
            'notes'        => $this->note,
            'status'       => 'pending',
        ]);

        session()->flash('message','Appointment booked successfully!');
        // Redirect to some route, e.g. "advisor.appointments"
        return redirect()->route('advisor.appointments');
    }

    public function render()
    {
        return view('livewire.advisor.add-appointment')->layout('layouts.app');
    }
}
