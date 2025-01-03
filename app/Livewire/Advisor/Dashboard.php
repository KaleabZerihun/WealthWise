<?php

namespace App\Livewire\Advisor;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.advisor.dashboard')->layout('layouts.app');
    }
}
