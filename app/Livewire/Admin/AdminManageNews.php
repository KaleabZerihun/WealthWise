<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\News;
use Carbon\Carbon;

class AdminManageNews extends Component
{
    public $newsItems = [];

    // For edit
    public $showEditModal = false;
    public $editId;
    public $editTitle;
    public $editContent;
    public $editCategory;
    public $editPublishedAt;

    public function mount()
    {
        $this->fetchNews();
    }

    // Retrieve items
    public function fetchNews()
    {
        // No search needed -> just order by published_at desc
        $this->newsItems = News::orderBy('published_at','desc')->get();
    }

    // Show edit modal
    public function openEditModal($id)
    {
        $item = News::findOrFail($id);

        $this->editId       = $id;
        $this->editTitle    = $item->title;
        $this->editContent  = $item->content;
        $this->editCategory = $item->category;

        // Fixing the "format()" on string issue:
        if ($item->published_at) {
            // parse it to a Carbon instance first
            $parsed = Carbon::parse($item->published_at);
            $this->editPublishedAt = $parsed->format('Y-m-d\TH:i');
        } else {
            $this->editPublishedAt = '';
        }

        $this->showEditModal = true;
    }

    // Update item
    public function updateNews()
    {
        $this->validate([
            'editTitle'       => 'required|string|max:255',
            'editContent'     => 'required|string',
            'editCategory'    => 'nullable|string|max:100',
            'editPublishedAt' => 'nullable|date',
        ]);

        $item = News::findOrFail($this->editId);

        $item->update([
            'title'        => $this->editTitle,
            'content'      => $this->editContent,
            'category'     => $this->editCategory,
            'published_at' => $this->editPublishedAt
                ? Carbon::parse($this->editPublishedAt)
                : null,
        ]);

        $this->showEditModal = false;
        $this->fetchNews();
        session()->flash('message','News item updated successfully!');
    }

    // Delete item
    public function deleteNews($id)
    {
        $item = News::findOrFail($id);
        $item->delete();

        $this->fetchNews();
        session()->flash('message','News item deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.admin-manage-news')->layout('layouts.app');
    }
}
