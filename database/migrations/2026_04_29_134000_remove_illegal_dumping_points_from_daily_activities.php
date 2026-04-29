<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            if (Schema::hasColumn('daily_activities', 'illegal_dumping_points_found')) {
                $table->dropColumn('illegal_dumping_points_found');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->unsignedInteger('illegal_dumping_points_found')->default(0)->after('gvp_points_found');
        });
    }
};
