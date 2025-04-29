<?php

namespace App\Livewire\Advisor;

use Livewire\Component;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;

class ManageEventRegistrations extends Component
{
    public $selectedClientId = null;
    public $clients = [];
    public $events = [];
    public $registeredEventIds = [];

    public function mount()
    {
        $this->clients = User::where('user_type', 'user')->get();
        $this->events = Event::orderBy('start_time', 'asc')->get();
    }

    public function updatedSelectedClientId()
    {
        $this->loadClientRegistrations();
    }

    public function loadClientRegistrations()
    {
        if ($this->selectedClientId) {
            $this->registeredEventIds = EventRegistration::where('user_id', $this->selectedClientId)
                ->pluck('event_id')
                ->toArray();
        } else {
            $this->registeredEventIds = [];
        }
    }

    public function registerClient($eventId)
    {
        if ($this->selectedClientId) {
            EventRegistration::firstOrCreate([
                'user_id' => $this->selectedClientId,
                'event_id' => $eventId,
            ]);
            $this->loadClientRegistrations();
            session()->flash('success', 'Client registered successfully!');
        }
    }

    public function unregisterClient($eventId)
    {
        if ($this->selectedClientId) {
            EventRegistration::where('user_id', $this->selectedClientId)
                ->where('event_id', $eventId)
                ->delete();
            $this->loadClientRegistrations();
            session()->flash('success', 'Client unregistered successfully!');
        }
    }

    public function render()
    {
        return view('livewire.advisor.manage-event-registrations')->layout('layouts.app');
    }
}
