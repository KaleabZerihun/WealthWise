<?php
namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;

class ManagePortfolio extends Component
{
    public $portfolioItems = [];
    public $client;

    // Properties for editing
    public $editingAssetId = null;
    public $editingAssetType;
    public $editingQuantity;
    public $editingValue;
    public $editingRoi;

    public $showEditModal = false;

    public function mount()
    {
        $this->client = Auth::user();
        $this->fetchPortfolioItems();
    }

    public function fetchPortfolioItems()
    {
        if ($this->client) {
            $this->portfolioItems = Portfolio::where('user_id', $this->client->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function openEditModal($id)
    {
        $asset = Portfolio::findOrFail($id);
        $this->editingAssetId = $id;
        $this->editingAssetType = $asset->asset_type;
        $this->editingQuantity = $asset->quantity;
        $this->editingValue = $asset->current_value;
        $this->editingRoi = $asset->return_on_investment;
        $this->showEditModal = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'editingAssetType' => 'required|string|max:255',
            'editingQuantity' => 'required|numeric|min:0',
            'editingValue' => 'required|numeric|min:0',
            'editingRoi' => 'required|numeric|min:0|max:100',
        ]);

        $asset = Portfolio::findOrFail($this->editingAssetId);
        $asset->asset_type = $this->editingAssetType;
        $asset->quantity = $this->editingQuantity;
        $asset->current_value = $this->editingValue;
        $asset->return_on_investment = $this->editingRoi;
        $asset->save();

        $this->showEditModal = false; // Close the modal
        $this->fetchPortfolioItems(); // Refresh the table
        session()->flash('message', 'Asset updated successfully!');
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function deleteAsset($id)
    {
        $asset = Portfolio::findOrFail($id);
        $asset->delete();
        $this->fetchPortfolioItems();
        session()->flash('message', 'Asset removed successfully!');
    }

    public function render()
    {
        return view('livewire.user.manage-portfolio')->layout('layouts.app');
    }
}

