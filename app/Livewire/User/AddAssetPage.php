<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;
class AddAssetPage extends Component
{
    public $asset_type = '';
    public $asset_id = '';
    public $quantity = '';
    public $purchase_date = '';
    public $current_value = '';
    public $risk_score = '';
    public $return_on_investment = '';
    public $asset_name = '';
    public $investment_amount = '';

    public function mount()
    {
        // Optionally, you can check if user is client or set up some guard logic
        $client = Auth::user();
        // If user is not a 'client', maybe redirect or handle error
    }

    public function store()
    {
        $this->return_on_investment = (($this->current_value - $this->investment_amount) / $this->investment_amount)*100;
        // Validate the form
        $this->validate([
            'asset_type' => 'required|string',
            'asset_name' => 'required|string',
            'investment_amount' => 'required|numeric',
            'quantity' => 'required|numeric',
            'purchase_date' => 'nullable|date',
            'current_value' => 'required|numeric',
            'risk_score' => 'nullable|numeric',
            'return_on_investment' => 'nullable|numeric',
        ]);

        $client = Auth::user();
        if (!$client) {
            // handle no client found (redirect, error, etc.)
            return;
        }

        // Create the new asset
        Portfolio::create([
            'user_id' => $client->id,
            'asset_type' => $this->asset_type,
            'asset_name' => $this->asset_name,
            'investment_amount' => $this->investment_amount,
            'quantity' => $this->quantity,
            'purchase_date' => $this->purchase_date,
            'current_value' => $this->current_value,
            'risk_score' => $this->risk_score ?: 0,
            'return_on_investment' => $this->return_on_investment ?: 0,
        ]);



        // redirect to the portfolio page
         return redirect()->route('portfolio.manage');
    }

    public function resetFormFields()
    {
        $this->asset_type = '';
        $this->asset_id = '';
        $this->asset_name = '';
        $this->investment_amount = '';
        $this->quantity = '';
        $this->purchase_date = '';
        $this->current_value = '';
        $this->risk_score = '';
        $this->return_on_investment = '';
    }
    public function render()
    {
        return view('livewire.user.add-asset-page')->layout('layouts.app');
    }
}
