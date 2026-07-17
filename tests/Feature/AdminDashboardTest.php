<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard_is_accessible_for_authenticated_admin(): void
    {
        Artisan::call('migrate:refresh', ['--force' => true]);

        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = \App\Models\User::create([
            'name' => 'Admin Tester',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Kehadiran & Keuangan');
    }

    public function test_admin_excel_export_returns_csv_content(): void
    {
        Artisan::call('migrate:refresh', ['--force' => true]);

        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = \App\Models\User::create([
            'name' => 'Admin Tester',
            'email' => 'admin2@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/admin/reports/export-excel');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertSee('Laporan Kehadiran dan Keuangan');
    }
}
