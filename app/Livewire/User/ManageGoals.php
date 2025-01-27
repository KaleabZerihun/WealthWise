<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\FinancialGoal;

class ManageGoals extends Component
{
    // Collection of all goals for the user
    public $goals = [];

    // Individual summary properties
    public $inProgress = 0;
    public $completed  = 0;
    public $onHold     = 0;
    public $total      = 0;

    // Edit modal properties
    public $showEditModal = false;
    public $editGoalId;

    // Fields for the editing form
    public $editGoalType;
    public $editTarget;
    public $editCurrent;
    public $editStartDate;
    public $editTargetDate;
    public $editStatus = 'in_progress';

    public function mount()
    {
        $this->fetchGoals();
    }

    // Load user's goals from DB, compute individual sums
    public function fetchGoals()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }


        $this->goals = FinancialGoal::where('user_id', $user->id)
            ->orderBy('created_at','desc')
            ->get();

        // Reset counters
        $this->inProgress = 0;
        $this->completed  = 0;
        $this->onHold     = 0;
        $this->total      = 0;

        // Tally each status
        foreach ($this->goals as $goal) {
            $this->total++;
            switch ($goal->goal_status) {
                case 'in_progress':
                    $this->inProgress++;
                    break;
                case 'completed':
                    $this->completed++;
                    break;
                case 'on_hold':
                    $this->onHold++;
                    break;
            }
        }
    }

    // Open the edit modal
    public function openEditModal($id)
    {
        $goal = FinancialGoal::findOrFail($id);

        $this->editGoalId   = $id;
        $this->editGoalType = $goal->goal_type;
        $this->editTarget   = $goal->target_amount;
        $this->editCurrent  = $goal->current_amount;
        $this->editStartDate= $goal->start_date;
        $this->editTargetDate = $goal->target_date;
        $this->editStatus   = $goal->goal_status;

        $this->showEditModal = true;
    }

    // Save the updated goal
    public function saveEdit()
    {
        $this->validate([
            'editGoalType' => 'required|string|max:255',
            'editTarget'   => 'required|numeric|min:0',
            'editCurrent'  => 'required|numeric|min:0',
            'editStatus'   => 'required|in:in_progress,completed,on_hold',
        ]);

        $goal = FinancialGoal::findOrFail($this->editGoalId);
        $goal->goal_type      = $this->editGoalType;
        $goal->target_amount  = $this->editTarget;
        $goal->current_amount = $this->editCurrent;
        $goal->start_date     = $this->editStartDate;
        $goal->target_date    = $this->editTargetDate;
        $goal->goal_status    = $this->editStatus;
        $goal->save();

        $this->showEditModal = false;
        $this->fetchGoals(); // refresh

        session()->flash('message', 'Goal updated successfully!');
    }

    // Close the edit modal
    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    // Delete a goal
    public function deleteGoal($id)
    {
        $goal = FinancialGoal::findOrFail($id);
        $goal->delete();

        $this->fetchGoals();
        session()->flash('message', 'Goal removed successfully!');
    }

    public function render()
    {
        return view('livewire.user.manage-goals')->layout('layouts.app');
    }
}
