<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\FinancialGoal;
class AddGoalPage extends Component
{
    // form fields
    public $goal_type       = '';
    public $target_amount   = '';
    public $current_amount  = '';
    public $start_date      = '';
    public $target_date     = '';
    public $goal_status     = 'in_progress';

    public function store()
    {
        $this->validate([
            'goal_type'      => 'required|string',
            'target_amount'  => 'required|numeric',
            'current_amount' => 'required|numeric',
            'start_date'     => 'nullable|date',
            'target_date'    => 'nullable|date',
            'goal_status'    => 'required|in:in_progress,completed,on_hold',
        ]);

        $user = Auth::user();
        if (!$user) {
            // handle not logged in or not client
            return;
        }

        // create the goal
        FinancialGoal::create([
            'user_id'      => $user->id,
            'goal_type'      => $this->goal_type,
            'target_amount'  => $this->target_amount,
            'current_amount' => $this->current_amount,
            'start_date'     => $this->start_date,
            'target_date'    => $this->target_date,
            'goal_status'    => $this->goal_status,
        ]);

         return redirect()->route('goals.manage');
    }

    public function resetForm()
    {
        $this->goal_type = '';
        $this->target_amount = '';
        $this->current_amount = '';
        $this->start_date = '';
        $this->target_date = '';
        $this->goal_status = 'in_progress';
    }
    public function render()
    {
        return view('livewire.user.add-goal-page')->layout('layouts.app');
    }
}
