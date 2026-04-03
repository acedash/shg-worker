<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->unsignedInteger('households_visited')->default(0);
            $table->unsignedInteger('households_started_segregation')->default(0);
            $table->unsignedInteger('households_started_home_composting')->default(0);
            $table->unsignedInteger('open_burning_issues_found')->default(0);
            $table->unsignedInteger('gvp_points_found')->default(0);
            $table->unsignedInteger('illegal_dumping_points_found')->default(0);
            $table->unsignedInteger('cd_waste_points_found')->default(0);
            $table->unsignedInteger('littering_points_found')->default(0);
            $table->unsignedInteger('open_defecation_points_found')->default(0);
            $table->unsignedInteger('yellow_red_spots_found')->default(0);
            $table->unsignedInteger('polluted_water_bodies_found')->default(0);
            $table->unsignedInteger('complaints_resolved')->default(0);
            $table->unsignedInteger('swm_assets_visited')->default(0);
            $table->unsignedInteger('non_functional_assets_made_functional')->default(0);
            $table->unsignedInteger('toilets_visited')->default(0);
            $table->unsignedInteger('toilet_issues_reported')->default(0);
            $table->unsignedInteger('toilet_issues_resolved')->default(0);
            $table->unsignedInteger('institutions_visited')->default(0);
            $table->unsignedInteger('institutions_started_composting')->default(0);
            $table->unsignedInteger('religious_places_visited')->default(0);
            $table->unsignedInteger('religious_places_with_separate_bins')->default(0);
            $table->unsignedInteger('religious_places_with_composting')->default(0);
            $table->unsignedInteger('transit_locations_visited')->default(0);
            $table->unsignedInteger('transit_locations_with_gvp')->default(0);
            $table->unsignedInteger('gvp_removed')->default(0);
            $table->unsignedInteger('yellow_red_spots_identified')->default(0);
            $table->unsignedInteger('yellow_red_spots_removed')->default(0);
            $table->unsignedInteger('households_sensitized_rrr')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'activity_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};
