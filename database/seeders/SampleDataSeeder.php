<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\College;
use App\Models\CollabRoom;
use App\Models\RestZone;
use App\Models\PenaltySetting;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $cce = College::where('code', 'CCE')->first();
        $con = College::where('code', 'CON')->first();
        $ccj = College::where('code', 'CCJ')->first();
        $coe = College::where('code', 'COE')->first();

        // Sample Books
        $books = [
            [
                'college_id'     => $cce?->id,
                'title'          => 'Clean Code',
                'author'         => 'Robert C. Martin',
                'publisher'      => 'Prentice Hall',
                'year_published' => 2008,
                'edition'        => '1st Edition',
                'isbn'           => '978-0132350884',
                'category'       => 'Technology',
                'program'        => 'BSIT',
                'shelf_location' => 'CCE-A1',
                'stock'          => 3,
                'description'    => 'A handbook of agile software craftsmanship.',
            ],
            [
                'college_id'     => $cce?->id,
                'title'          => 'Introduction to Algorithms',
                'author'         => 'Thomas H. Cormen',
                'publisher'      => 'MIT Press',
                'year_published' => 2009,
                'edition'        => '3rd Edition',
                'isbn'           => '978-0262033848',
                'category'       => 'Technology',
                'program'        => 'BSCS',
                'shelf_location' => 'CCE-A2',
                'stock'          => 2,
                'description'    => 'Comprehensive introduction to algorithms.',
            ],
            [
                'college_id'     => $con?->id,
                'title'          => 'Fundamentals of Nursing',
                'author'         => 'Patricia Potter',
                'publisher'      => 'Elsevier',
                'year_published' => 2020,
                'edition'        => '10th Edition',
                'isbn'           => '978-0323677721',
                'category'       => 'Medical',
                'program'        => 'BSN',
                'shelf_location' => 'CON-B1',
                'stock'          => 5,
                'description'    => 'Core nursing concepts and skills.',
            ],
            [
                'college_id'     => $con?->id,
                'title'          => 'Anatomy & Physiology',
                'author'         => 'Elaine Marieb',
                'publisher'      => 'Pearson',
                'year_published' => 2018,
                'edition'        => '10th Edition',
                'isbn'           => '978-0134580999',
                'category'       => 'Medical',
                'program'        => 'BSN',
                'shelf_location' => 'CON-B2',
                'stock'          => 4,
                'description'    => 'Human anatomy and physiology textbook.',
            ],
            [
                'college_id'     => $ccj?->id,
                'title'          => 'Criminal Law',
                'author'         => 'Luis Reyes',
                'publisher'      => 'Rex Bookstore',
                'year_published' => 2019,
                'edition'        => '2nd Edition',
                'isbn'           => '978-9712345678',
                'category'       => 'Law',
                'program'        => 'BSCrim',
                'shelf_location' => 'CCJ-C1',
                'stock'          => 4,
                'description'    => 'Philippine criminal law fundamentals.',
            ],
            [
                'college_id'     => $coe?->id,
                'title'          => 'Engineering Mechanics',
                'author'         => 'Russell Hibbeler',
                'publisher'      => 'Pearson',
                'year_published' => 2016,
                'edition'        => '14th Edition',
                'isbn'           => '978-0133918922',
                'category'       => 'Engineering',
                'program'        => 'BSCE',
                'shelf_location' => 'COE-D1',
                'stock'          => 2,
                'description'    => 'Statics and dynamics for engineers.',
            ],
            [
                'college_id'     => null,
                'title'          => 'The Alchemist',
                'author'         => 'Paulo Coelho',
                'publisher'      => 'HarperCollins',
                'year_published' => 1988,
                'edition'        => '25th Anniversary Edition',
                'isbn'           => '978-0062315007',
                'category'       => 'Fiction',
                'program'        => 'General',
                'shelf_location' => 'GEN-E1',
                'stock'          => 1,
                'description'    => 'A philosophical novel about following your dreams.',
            ],
            [
                'college_id'     => null,
                'title'          => 'Atomic Habits',
                'author'         => 'James Clear',
                'publisher'      => 'Avery',
                'year_published' => 2018,
                'edition'        => '1st Edition',
                'isbn'           => '978-0735211292',
                'category'       => 'Self-Help',
                'program'        => 'General',
                'shelf_location' => 'GEN-E2',
                'stock'          => 3,
                'description'    => 'Tiny changes, remarkable results.',
            ],
        ];

        foreach ($books as $book) {
            Book::firstOrCreate(
                ['isbn' => $book['isbn']],
                $book
            );
        }

        // Collab Rooms
        $rooms = [
            ['name' => 'Collab Room A', 'capacity' => 12, 'status' => 'available', 'rules' => 'Min 3 students. Max 3 hrs/day. Whiteboard, projector, AC.'],
            ['name' => 'Collab Room B', 'capacity' => 12, 'status' => 'available', 'rules' => 'Min 3 students. Max 3 hrs/day. Projector, AC.'],
            ['name' => 'Discussion Pod', 'capacity' => 8,  'status' => 'available', 'rules' => 'Min 3 students. Max 3 hrs/day. Quiet group space.'],
        ];

        foreach ($rooms as $room) {
            CollabRoom::firstOrCreate(['name' => $room['name']], $room);
        }

        // Rest Zones
        $zones = [
            ['name' => 'Rest Zone 1', 'capacity' => 15],
            ['name' => 'Rest Zone 2', 'capacity' => 15],
        ];

        foreach ($zones as $zone) {
            RestZone::firstOrCreate(['name' => $zone['name']], $zone);
        }

        // Penalty Settings
        PenaltySetting::firstOrCreate(
            ['id' => 1],
            ['daily_fine_rate' => 5.00, 'updated_by' => 1]
        );
    }
}