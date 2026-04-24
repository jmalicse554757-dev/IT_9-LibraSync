<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            ['name' => 'College of Computer Education',      'code' => 'CCE'],
            ['name' => 'College of Nursing',                 'code' => 'CON'],
            ['name' => 'College of Criminal Justice',        'code' => 'CCJ'],
            ['name' => 'College of Engineering',             'code' => 'COE'],
            ['name' => 'College of Business Administration', 'code' => 'CBA'],
            ['name' => 'College of Education',               'code' => 'CED'],
            ['name' => 'College of Arts and Sciences',       'code' => 'CAS'],
        ];

        foreach ($colleges as $college) {
            College::firstOrCreate(['code' => $college['code']], $college);
        }
    }
}