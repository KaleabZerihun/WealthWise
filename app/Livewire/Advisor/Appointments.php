<?php

namespace App\Livewire\Advisor;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Appointments extends Component
{public $appointments = [];

    // Edit Modal
    public $showEditModal = false;
    public $editingAppointmentId;
    public $editingScheduledAt;
    public $editingNotes;

    // New Appointment Modal
    public $showNewModal = false;
    public $newUserId;       // which user is the appointment with
    public $newScheduledAt;
    public $newNotes;

    public function mount()
    {
        $advisor = Auth::guard('advisor')->user();
        if ($advisor) {
            $this->loadAppointments($advisor->id);
        }
    }

    protected function loadAppointments($advisorId)
    {
        $this->appointments = Appointment::where('advisor_id', $advisorId)
            ->orderBy('scheduled_at', 'asc')
            ->get();
    }

    /**
     * Cancel an appointment (status => 'canceled').
     */
    public function cancelAppointment($id)
    {
        $advisor = Auth::guard('advisor')->user();
        $appt = Appointment::findOrFail($id);

        $appt->delete();
        $this->loadAppointments($advisor);

        session()->flash('message', 'Appointment canceled successfully.');
    }

    /**
     * Open the edit modal with an appointment's data.
     */
    public function openEditModal($appointmentId)
    {
        $advisor = Auth::guard('advisor')->user();
        if (!$advisor) return;

        $appt = Appointment::where('advisor_id', $advisor->id)
            ->where('id', $appointmentId)
            ->firstOrFail();

        $this->editingAppointmentId  = $appt->id;
        $this->editingScheduledAt    = $appt->scheduled_at->format('Y-m-d\TH:i');
        $this->editingNotes          = $appt->notes;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingAppointmentId = null;
        $this->editingScheduledAt   = '';
        $this->editingNotes         = '';
    }

    /**
     * Save changes to the appointment (reschedule, update notes, etc.).
     */
    public function updateAppointment()
    {
        $advisor = Auth::guard('advisor')->user();
        if (!$advisor) return;

        $appt = Appointment::where('advisor_id', $advisor->id)
            ->where('id', $this->editingAppointmentId)
            ->firstOrFail();

        // Validate
        $this->validate([
            'editingScheduledAt' => 'required|date',
            'editingNotes'       => 'nullable|string|max:1000',
        ]);

        $appt->scheduled_at = Carbon::parse($this->editingScheduledAt);
        $appt->notes        = $this->editingNotes;
        $appt->save();

        $this->closeEditModal();
        $this->loadAppointments($advisor->id);
        session()->flash('message','Appointment updated!');
    }

    /**
     * Open modal to create a new appointment.
     */
    public function openNewModal()
    {
        $this->newUserId       = '';
        $this->newScheduledAt  = '';
        $this->newNotes        = '';
        $this->showNewModal    = true;
    }

    public function closeNewModal()
    {
        $this->showNewModal = false;
        $this->newUserId      = '';
        $this->newScheduledAt = '';
        $this->newNotes       = '';
    }

    /**
     * Create a new appointment for the chosen user.
     */
    public function createAppointment()
    {
        $advisor = Auth::guard('advisor')->user();
        if (!$advisor) return;

        // Basic validation
        $this->validate([
            'newUserId'      => 'required|exists:users,id',
            'newScheduledAt' => 'required|date|after:now',
            'newNotes'       => 'nullable|string|max:1000',
        ]);

        Appointment::create([
            'user_id'     => $this->newUserId,
            'advisor_id'  => $advisor->id,
            'scheduled_at'=> Carbon::parse($this->newScheduledAt),
            'status'      => 'pending',
            'notes'       => $this->newNotes,
        ]);

        $this->closeNewModal();
        $this->loadAppointments($advisor->id);
        session()->flash('message','New appointment created successfully!');
    }
    public function render()
    {
        return view('livewire.advisor.appointments')->layout('layouts.app');
    }
}
