<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyFinalRemark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_month',
        'remark',
        'source_segregation',
        'home_composting',
        'swm_infrastructure_functionality',
        'rrr_centre_awareness',
        'public_grievance_redressal',
        'change_in_public_behavior',
        'overall_improvement',
        'other_feedback',
    ];

    protected function casts(): array
    {
        return [
            'report_month' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
