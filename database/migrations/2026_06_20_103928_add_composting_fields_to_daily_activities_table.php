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
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->integer('households_motivated_composting')->nullable()->after('wet_waste_collected');
            $table->integer('households_started_composting')->nullable()->after('households_motivated_composting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->dropColumn([
                'households_motivated_composting',
                'households_started_composting'
            ]);
        });
    }
};
