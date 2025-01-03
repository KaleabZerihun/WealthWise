<?php

namespace App\Livewire\User;

use Livewire\Component;

class Appointments extends Component
{
    public function render()
    {
        return view('livewire.user.appointments')->layout('layouts.app');
    }
}
