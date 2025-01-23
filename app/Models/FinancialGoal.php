<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    protected $table = 'financial_goals';

    protected $fillable = [
        'user_id',
        'goal_type',
        'target_amount',
        'current_amount',
        'start_date',
        'target_date',
        'goal_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
