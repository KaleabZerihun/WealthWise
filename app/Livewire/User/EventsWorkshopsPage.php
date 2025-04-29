<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;

class EventsWorkshopsPage extends Component
{
    public function register($eventId)
    {
        EventRegistration::firstOrCreate([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
        ]);

        session()->flash('success', 'Successfully registered for the event!');
    }
    public function unregister($eventId)
    {
        EventRegistration::where('user_id', auth()->id())
            ->where('event_id', $eventId)
            ->delete();

        session()->flash('success', 'Successfully unregistered from the event.');
    }


    public function render()
    {
        $events = Event::orderBy('start_time', 'asc')->get();
        $myRegistrations = EventRegistration::where('user_id', Auth::id())
            ->pluck('event_id')
            ->toArray();

        return view('livewire.user.events-workshops-page', [
            'events' => $events,
            'myRegistrations' => $myRegistrations,
        ])->layout('layouts.app');
    }
}
