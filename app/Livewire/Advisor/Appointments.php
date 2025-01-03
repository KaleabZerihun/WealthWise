<?php

namespace App\Livewire\Advisor;

use Livewire\Component;

class Appointments extends Component
{
    public function render()
    {
        return view('livewire.advisor.appointments')->layout('layouts.app');
    }
}
