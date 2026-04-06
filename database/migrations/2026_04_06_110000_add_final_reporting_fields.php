<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_activities', function (Blueprint $table) {
            $table->unsignedInteger('commercial_shops_visited')->default(0)->after('households_visited');
            $table->unsignedInteger('complaints_received')->default(0)->after('polluted_water_bodies_found');
        });

        Schema::table('monthly_final_remarks', function (Blueprint $table) {
            $table->text('source_segregation')->nullable()->after('remark');
            $table->text('home_composting')->nullable()->after('source_segregation');
            $table->text('swm_infrastructure_functionality')->nullable()->after('home_composting');
            $table->text('rrr_centre_awareness')->nullable()->after('swm_infrastructure_functionality');
            $table->text('public_grievance_redressal')->nullable()->after('rrr_centre_awareness');
            $table->text('change_in_public_behavior')->nullable()->after('public_grievance_redressal');
            $table->text('overall_improvement')->nullable()->after('change_in_public_behavior');
            $table->text('other_feedback')->nullable()->after('overall_improvement');
        });

        DB::table('monthly_final_remarks')
            ->whereNotNull('remark')
            ->update([
                'other_feedback' => DB::raw('remark'),
            ]);
    }

    public function down(): void
    {
        Schema::table('monthly_final_remarks', function (Blueprint $table) {
            $table->dropColumn([
                'source_segregation',
                'home_composting',
                'swm_infrastructure_functionality',
                'rrr_centre_awareness',
                'public_grievance_redressal',
                'change_in_public_behavior',
                'overall_improvement',
                'other_feedback',
            ]);
        });

        Schema::table('daily_activities', function (Blueprint $table) {
            $table->dropColumn([
                'commercial_shops_visited',
                'complaints_received',
            ]);
        });
    }
};
