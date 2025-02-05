<?php

namespace App\Livewire\User;

use App\Models\News;
use Livewire\Component;

class NewsPage extends Component
{
    public $selectedItem = null;       // The currently selected news item for "Read More"
    public $showReadMoreModal = false; // Whether the detail modal is displayed

    public function render()
    {
        // Simply fetch all news items, ordered by published_at descending
        $newsItems = News::orderBy('published_at','desc')->get();

        return view('livewire.user.news-page', [
            'newsItems' => $newsItems
        ])->layout('layouts.app');
    }

    // When user clicks "Read More"
    public function openReadMoreModal($id)
    {
        $this->selectedItem = News::findOrFail($id);
        $this->showReadMoreModal = true;
    }

    // Closes the detail modal
    public function closeReadMoreModal()
    {
        $this->showReadMoreModal = false;
        $this->selectedItem = null;
    }
}
