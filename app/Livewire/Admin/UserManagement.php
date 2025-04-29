<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Advisor;
use App\Models\Admin;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    /* ───────── searchable / filterable ───────── */
    public string $filter = 'all';      // all | clients | advisors | admins | unverified
    public string $search = '';

    /* ───────── edit / delete modals ───────── */
    public bool   $showEditModal   = false;
    public bool   $showDeleteModal = false;

    public int    $editingId       = 0;      // primary-key of the record
    public string $editingType     = '';     // user | advisor | admin

    /*  basic editable fields – add more if you need them  */
    public string $first_name = '';
    public string $last_name  = '';
    public string $email      = '';

    /* ─────── reset paginator when inputs change ─────── */
    public function updatingFilter() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    /* ═══════ data query ═══════ */
    public function getRows()
    {
        $q = match ($this->filter) {
            'clients'  => User::query()->where('user_type', 'user'),
            'advisors' => Advisor::query(),
            'admins'   => Admin::query(),
            default    => User::query(),            // “all” or “unverified”
        };

        if ($this->filter === 'unverified') {
            $q->whereNull('email_verified_at');
        }

        if ($this->search !== '') {
            $q->where(function ($sub) {
                $sub->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name',  'like', "%{$this->search}%")
                    ->orWhere('email',      'like', "%{$this->search}%");
            });
        }

        return $q->orderByDesc('created_at')->paginate(10);
    }

    /* ═══════ helpers ═══════ */
    private function findModel(int $id, string $type)
    {
        return match ($type) {
            'advisor' => Advisor::findOrFail($id),
            'admin'   => Admin::findOrFail($id),
            default   => User::findOrFail($id),   // “user”
        };
    }

    /* ═══════ EDIT ─ open / validate / save ═══════ */
    public function openEditModal(int $id, string $type)
    {
        $m = $this->findModel($id, $type);

        $this->editingId   = $id;
        $this->editingType = $type;

        $this->first_name  = $m->first_name;
        $this->last_name   = $m->last_name;
        $this->email       = $m->email;

        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $model = $this->findModel($this->editingId, $this->editingType);

        $rules = [
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => ['required','string','email','max:255',
                Rule::unique($model->getTable())->ignore($model->id)],
        ];

        $data = $this->validate($rules);

        $model->update($data);

        $this->showEditModal = false;
        session()->flash('message', 'User updated successfully!');
        $this->resetPage();
    }

    /* ═══════ DELETE ═══════ */
    public function confirmDelete(int $id, string $type)
    {
        $this->editingId       = $id;
        $this->editingType     = $type;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        $this->findModel($this->editingId, $this->editingType)->delete();

        $this->showDeleteModal = false;
        session()->flash('message', 'User removed permanently.');
        $this->resetPage();
    }

    /* ═══════  VIEW  ═══════ */
    public function render()
    {
        return view('livewire.admin.user-management', [
            'rows' => $this->getRows(),
        ])->layout('layouts.app');
    }
}
