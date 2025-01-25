<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = [
        'user_id',
        'asset_type',
        'asset_name',
        'investment_amount',
        'quantity',
        'purchase_date',
        'current_value',
        'risk_score',
        'return_on_investment',
    ];

    // Relationship: portfolio belongs to a client
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
