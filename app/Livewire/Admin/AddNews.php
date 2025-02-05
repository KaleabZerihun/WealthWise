<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\News;
use Carbon\Carbon;

class AddNews extends Component
{
    public $title = '';
    public $content = '';
    public $category = '';
    public $published_at = '';

    public function storeNews()
    {
        $this->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'category'     => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);

        News::create([
            'title'        => $this->title,
            'content'      => $this->content,
            'category'     => $this->category ?: null,
            'published_at' => $this->published_at ? Carbon::parse($this->published_at) : null,
        ]);

        session()->flash('message','News created successfully!');
        return redirect()->route('admin.news');
    }

    public function render()
    {
        return view('livewire.admin.add-news')->layout('layouts.app');
    }
}
