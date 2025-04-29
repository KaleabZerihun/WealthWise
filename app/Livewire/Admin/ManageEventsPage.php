<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithPagination;
use Carbon\Carbon;

class ManageEventsPage extends Component
{
    use WithPagination;

    public $title, $description, $start_time, $speaker, $location;
    public $eventId; // To track if editing an existing event

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'start_time' => 'required|date',
        'speaker' => 'required|string|max:255',
        'location' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        if ($this->eventId) {
            // Update
            $event = Event::findOrFail($this->eventId);
            $event->update([
                'title' => $this->title,
                'description' => $this->description,
                'start_time' => $this->start_time,
                'speaker' => $this->speaker,
                'location' => $this->location,
            ]);

            session()->flash('success', 'Event updated successfully.');
        } else {
            // Create
            Event::create([
                'title' => $this->title,
                'description' => $this->description,
                'start_time' => $this->start_time,
                'speaker' => $this->speaker,
                'location' => $this->location,
            ]);

            session()->flash('success', 'Event created successfully.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->start_time = Carbon::parse($event->start_time)->format('Y-m-d\TH:i');
        $this->speaker = $event->speaker;
        $this->location = $event->location;
    }

    public function delete($id)
    {
        Event::findOrFail($id)->delete();
        session()->flash('success', 'Event deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'start_time', 'speaker', 'location', 'eventId']);
    }

    public function render()
    {
        $events = Event::orderBy('start_time', 'desc')->paginate(10);

        return view('livewire.admin.manage-events-page', [
            'events' => $events,
        ])->layout('layouts.app');
    }
}
