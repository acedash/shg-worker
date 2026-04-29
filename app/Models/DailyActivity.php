<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_date',
        'households_visited',
        'commercial_shops_visited',
        'institutions_visited',
        'religious_places_visited',
        'transit_locations_visited',
        'toilets_visited',
        'swm_assets_visited',
        'households_started_segregation',
        'households_started_home_composting',
        'open_burning_issues_found',
        'gvp_points_found',
        'cd_waste_points_found',
        'littering_points_found',
        'open_defecation_points_found',
        'yellow_red_spots_found',
        'polluted_water_bodies_found',
        'complaints_received',
        'complaints_resolved',
        'non_functional_assets_made_functional',
        'toilet_issues_reported',
        'toilet_issues_resolved',
        'institutions_started_composting',
        'religious_places_with_separate_bins',
        'religious_places_with_composting',
        'transit_locations_with_gvp',
        'gvp_removed',
        'yellow_red_spots_identified',
        'yellow_red_spots_removed',
        'households_sensitized_rrr',
        'remarks',
        'photo_paths',
        'document_paths',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'photo_paths' => 'array',
            'document_paths' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
