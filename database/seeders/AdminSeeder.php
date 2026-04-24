<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\College;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get any college for admin
        $college = College::where('code', 'CCE')->first();

        User::firstOrCreate(
            ['email' => 'admin@librasync.com'],
            [
                'college_id'     => $college?->id,
                'first_name'     => 'System',
                'last_name'      => 'Admin',
                'email'          => 'admin@librasync.com',
                'password'       => Hash::make('admin1234'),
                'role'           => 'admin',
                'status'         => 'active',
                'gender'         => 'male',
                'date_of_birth'  => '1990-01-01',
                'contact_number' => '09000000000',
                'program'        => 'Administration',
                'year_level'     => null,
                'section'        => null,
            ]
        );

        // Sample librarian account
        User::firstOrCreate(
            ['email' => 'librarian@librasync.com'],
            [
                'college_id'     => $college?->id,
                'first_name'     => 'Maria',
                'last_name'      => 'Santos',
                'email'          => 'librarian@librasync.com',
                'password'       => Hash::make('librarian1234'),
                'role'           => 'librarian',
                'status'         => 'active',
                'gender'         => 'female',
                'date_of_birth'  => '1992-05-15',
                'contact_number' => '09111111111',
                'employee_id'    => 'EMP-0001',
                'program'        => 'Library Science',
                'year_level'     => null,
                'section'        => null,
            ]
        );

        // Sample student account
        User::firstOrCreate(
            ['email' => 'student@librasync.com'],
            [
                'college_id'     => $college?->id,
                'first_name'     => 'Juan',
                'last_name'      => 'Dela Cruz',
                'email'          => 'student@librasync.com',
                'password'       => Hash::make('student1234'),
                'role'           => 'student',
                'status'         => 'active',
                'gender'         => 'male',
                'date_of_birth'  => '2004-01-15',
                'contact_number' => '09222222222',
                'student_id'     => '2024-0001',
                'program'        => 'BS Information Technology',
                'year_level'     => '2nd Year',
                'section'        => 'A',
            ]
        );
    }
}