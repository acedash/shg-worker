<?php

namespace Tests\Feature;

use App\Models\DailyActivity;
use App\Models\District;
use App\Models\User;
use App\Models\Ulb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_worker_can_register_and_submit_daily_activity(): void
    {
        Storage::fake('public');

        $district = District::create(['name' => 'Bhopal']);
        $ulb = Ulb::create([
            'district_id' => $district->id,
            'name' => 'Bhopal ULB',
            'status' => 'Municipal Council',
            'code' => 'TEST001',
        ]);

        $response = $this->post('/register', [
            'name' => 'Worker One',
            'phone' => '9876543210',
            'district_id' => $district->id,
            'ulb_id' => $ulb->id,
            'email' => 'worker@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('worker.dashboard'));
        $this->assertAuthenticated();

        $submit = $this->post(route('worker.daily-activity.store'), [
            'activity_date' => '2026-04-02',
            'households_visited' => 10,
            'complaints_resolved' => 2,
            'remarks' => 'Field visit completed.',
            'photos' => [
                UploadedFile::fake()->image('proof-one.jpg'),
            ],
        ]);

        $submit->assertRedirect();
        $this->assertDatabaseHas('daily_activities', [
            'households_visited' => 10,
            'complaints_resolved' => 2,
        ]);

        $activity = DailyActivity::first();
        $this->assertNotEmpty($activity->photo_paths);
        Storage::disk('public')->assertExists($activity->photo_paths[0]);
    }

    public function test_admin_dashboard_requires_admin_role(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_worker_can_view_daily_report_and_open_monthly_whatsapp_share(): void
    {
        $worker = User::factory()->create([
            'role' => 'worker',
            'district_name' => 'Jammu',
            'ulb_name' => 'Jammu Municipal Corporation',
        ]);

        $activity = DailyActivity::create([
            'user_id' => $worker->id,
            'activity_date' => '2026-04-02',
            'households_visited' => 8,
            'complaints_resolved' => 1,
            'remarks' => 'Completed field visit.',
        ]);

        $dailyResponse = $this->actingAs($worker)->get(route('worker.reports.daily', ['activity' => $activity->id]));
        $dailyResponse->assertOk();
        $dailyResponse->assertSee('Daily Activity Report');
        $dailyResponse->assertSee('Completed field visit.');

        $monthlyShareResponse = $this->actingAs($worker)->get(route('worker.reports.monthly.whatsapp', ['month' => '2026-04']));
        $monthlyShareResponse->assertRedirect();
        $this->assertStringContainsString('wa.me', $monthlyShareResponse->headers->get('Location'));
    }

    public function test_worker_can_open_submissions_page(): void
    {
        $worker = User::factory()->create([
            'role' => 'worker',
        ]);

        DailyActivity::create([
            'user_id' => $worker->id,
            'activity_date' => '2026-04-02',
            'households_visited' => 4,
            'remarks' => 'Morning round done.',
        ]);

        $response = $this->actingAs($worker)->get(route('worker.submissions', ['month' => '2026-04']));

        $response->assertOk();
        $response->assertSeeText('Daily Activity Logs');
        $response->assertSeeText('Morning round done.');
    }

    public function test_worker_can_save_final_monthly_remark_and_see_it_in_export(): void
    {
        $worker = User::factory()->create([
            'role' => 'worker',
            'district_name' => 'Jammu',
            'ulb_name' => 'Jammu Municipal Corporation',
        ]);

        DailyActivity::create([
            'user_id' => $worker->id,
            'activity_date' => '2026-04-02',
            'households_visited' => 5,
            'remarks' => 'Daily work completed.',
        ]);

        $saveResponse = $this->actingAs($worker)->post(route('worker.reports.monthly.final-remark'), [
            'month' => '2026-04',
            'final_remark' => 'April work completed successfully.',
        ]);

        $saveResponse->assertRedirect(route('worker.submissions', ['month' => '2026-04']));
        $this->assertDatabaseHas('monthly_final_remarks', [
            'user_id' => $worker->id,
            'remark' => 'April work completed successfully.',
        ]);

        $exportResponse = $this->actingAs($worker)->get(route('worker.reports.monthly', ['month' => '2026-04']));
        $exportResponse->assertOk();
        $this->assertStringContainsString('Final Monthly Remark', $exportResponse->streamedContent());
        $this->assertStringContainsString('April work completed successfully.', $exportResponse->streamedContent());

        $pdfResponse = $this->actingAs($worker)->get(route('worker.reports.monthly.pdf', ['month' => '2026-04']));
        $pdfResponse->assertOk();
        $this->assertSame('application/pdf', $pdfResponse->headers->get('content-type'));
    }

    public function test_worker_can_update_existing_final_monthly_remark(): void
    {
        $worker = User::factory()->create([
            'role' => 'worker',
        ]);

        $this->actingAs($worker)->post(route('worker.reports.monthly.final-remark'), [
            'month' => '2026-04',
            'final_remark' => 'First remark.',
        ]);

        $updateResponse = $this->actingAs($worker)->post(route('worker.reports.monthly.final-remark'), [
            'month' => '2026-04',
            'final_remark' => 'Updated remark.',
        ]);

        $updateResponse->assertRedirect(route('worker.submissions', ['month' => '2026-04']));
        $this->assertDatabaseHas('monthly_final_remarks', [
            'user_id' => $worker->id,
            'remark' => 'Updated remark.',
        ]);
        $this->assertDatabaseCount('monthly_final_remarks', 1);
    }
}
