<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Finance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();

        if (! $student) {
            $this->command->warn('Tidak ada data murid, lewati seeding demo.');
            return;
        }

        Activity::create([
            'title' => 'Ibadah Raya Minggu Pagi',
            'description' => 'Ibadah bersama seluruh anak sekolah minggu.',
            'date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:30:00',
            'location' => 'Ruang Utama',
            'category' => 'Ibadah',
            'created_by' => 1,
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'notes' => 'Datang tepat waktu',
            'recorded_by' => 1,
        ]);

        Finance::create([
            'student_id' => $student->id,
            'type' => 'pemasukan',
            'description' => 'Donasi minggu',
            'amount' => 250000,
            'date' => now()->toDateString(),
            'category' => 'Donasi',
            'recorded_by' => 1,
            'notes' => 'Donatur dari jemaat',
        ]);

        Finance::create([
            'student_id' => $student->id,
            'type' => 'pengeluaran',
            'description' => 'Belanja alat tulis',
            'amount' => 180000,
            'date' => now()->toDateString(),
            'category' => 'Perlengkapan',
            'recorded_by' => 1,
            'notes' => 'Untuk kegiatan anak',
        ]);
    }
}
