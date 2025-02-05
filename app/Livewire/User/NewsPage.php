<?php

namespace App\Livewire\User;

use App\Models\News;
use Livewire\Component;

class NewsPage extends Component
{
    public $selectedItem = null;
    public $showReadMoreModal = false;

    public function render()
    {
        $newsItems = News::orderBy('published_at','desc')->get();

        return view('livewire.user.news-page', [
            'newsItems' => $newsItems
        ])->layout('layouts.app');
    }

    public function openReadMoreModal($id)
    {
        $this->selectedItem = News::findOrFail($id);
        $this->showReadMoreModal = true;
    }

    public function closeReadMoreModal()
    {
        $this->showReadMoreModal = false;
        $this->selectedItem = null;
    }
}
