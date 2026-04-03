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
        'households_started_segregation',
        'households_started_home_composting',
        'open_burning_issues_found',
        'gvp_points_found',
        'illegal_dumping_points_found',
        'cd_waste_points_found',
        'littering_points_found',
        'open_defecation_points_found',
        'yellow_red_spots_found',
        'polluted_water_bodies_found',
        'complaints_resolved',
        'swm_assets_visited',
        'non_functional_assets_made_functional',
        'toilets_visited',
        'toilet_issues_reported',
        'toilet_issues_resolved',
        'institutions_visited',
        'institutions_started_composting',
        'religious_places_visited',
        'religious_places_with_separate_bins',
        'religious_places_with_composting',
        'transit_locations_visited',
        'transit_locations_with_gvp',
        'gvp_removed',
        'yellow_red_spots_identified',
        'yellow_red_spots_removed',
        'households_sensitized_rrr',
        'remarks',
        'photo_paths',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'photo_paths' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
