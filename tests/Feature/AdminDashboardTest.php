<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_overview_page_is_accessible_for_authenticated_admin(): void
    {
        Artisan::call('migrate:refresh', ['--force' => true]);

        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = \App\Models\User::create([
            'name' => 'Admin Tester Overview',
            'email' => 'admin-overview@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
        $response->assertSee('Kelola Role');
        $response->assertSee('Kelola User');
        $response->assertSee('Kelola Murid');
    }

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
        $response->assertSee('Grafik Kehadiran');
        $response->assertSee('Minggu');
        $response->assertSee('Bulanan');
    }

    public function test_admin_role_dashboard_shows_attendance_chart(): void
    {
        Artisan::call('migrate:refresh', ['--force' => true]);

        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = \App\Models\User::create([
            'name' => 'Admin Tester 2',
            'email' => 'admin3@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Grafik Kehadiran');
    }

    public function test_admin_dashboard_shows_date_range_filter_inputs(): void
    {
        Artisan::call('migrate:refresh', ['--force' => true]);

        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = \App\Models\User::create([
            'name' => 'Admin Tester 3',
            'email' => 'admin4@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dari');
        $response->assertSee('Hingga');
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
