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
    public $editingAssetName;
    public $editingQuantity;
    public $editingValue;
    public $editingRoi;
    public $editingAssetType;

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
        $this->editingAssetName = $asset->asset_name;
        $this->editingQuantity = $asset->quantity;
        $this->editingValue = $asset->current_value;
        $this->editingRoi = $asset->return_on_investment;
        $this->showEditModal = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'editingAssetType' => 'required|string|max:255',
            'editingAssetName' => 'required|string|max:255',
            'editingQuantity' => 'required|numeric|min:0',
            'editingValue' => 'required|numeric|min:0',
        ]);

        $asset = Portfolio::findOrFail($this->editingAssetId);
        $asset->asset_type = $this->editingAssetType;
        $asset->asset_name = $this->editingAssetName;
        $asset->quantity = $this->editingQuantity;
        $asset->current_value = $this->editingValue;
        $asset->return_on_investment = (($this->editingValue - $asset->investment_amount) / $asset->investment_amount)*100;
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

