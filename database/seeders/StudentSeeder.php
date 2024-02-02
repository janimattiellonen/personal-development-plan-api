<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    protected array $firstNames = [
        'Aldo',
        'Aleyn',
        'Alford',
        'Amherst',
        'Angel',
        'Anson',
        'Archibald',
        'Aries',
        'Arwen',
        'Astin',
        'Atley',
        'Atwell',
        'Audie',
        'Avery',
        'Ayers',
        'Baker',
        'Balder',
        'Ballentine',
        'Bardalph',
        'Barker',
        'Barric',
        'Bayard',
        'Ethelbert',
        'Ethelred',
        'Ethelwolf',
        'Everest',
        'Ewing',
        'Falkner',
        'Falstaff',
        'Farnell',
        'Farold',
        'Farran',
        'Fenton',
        'Ludlow',
        'Lynton',
        'Maddox',
        'Mallin',
        'Mander',
        'Mansfield',
        'Markham',
        'Marland',
        'Marley',
        'Marrock',
        'Marsh',
        'Marston',
        'Martin',
        'Marvin',
        'Wadsworth',
        'Wain',
        'Waite',
        'Walcott',
        'Wales',
        'Walford',
        'Walfred',
        'Walker',
        'Waller',
        'Walmir',
        'Walsh',
        'Walworth',
        'Walwyn',
        'Warburton',
        'Ward',
        'Warden',
        'Wardford',
        'Wardley',
        'Ware',
        'Waring',
        'Warley ',
    ];

    protected array $lastNames = [
        'Whitlock',
        'Whitlow',
        'Whitman',
        'Whitmarsh',
        'Whitmer',
        'Whitmill',
        'Whitmore',
        'Whitmore',
        'Whitmore',
        'Whitney',
        'Whiton',
        'Whitsett',
        'Whitsitt',
        'Whitt',
        'Whittaker',
        'Whittall',
        'Whittemore',
        'Whittenton',
        'Whitter',
        'Whittier',
        'Whitting',
        'Whittingham',
        'Whittington',
        'Whittle',
        'Whittle',
        'Whittlesey',
        'Whittley',
        'Whittum',
        'Whitty',
        'Whitty',
        'Whitus',
        'Whitwell',
        'Whitworth',
        'Whoolery',
        'Whorley',
        'Whorton',
        'Whybrew',
        'Whyde',
        'Whyman',
        'Wice',
        'Wich',
        'Wick',
        'Wick',
        'Wicke',
        'Wicken',
        'Wickens',
        'Wicker',
        'Wickerham',
        'Wickers',
        'Wickersham',
        'Wickes',
        'Kaiser',
        'Holloway',
        'Pugh',
        'Castillo',
        'Faulkner',
        'Hubbard',
        'Holden',
        'Humphrey',
        'Arroyo',
        'Lucero',
        'Daniel',
        'Patterson',
    ];

    protected $types = [
        'student',
        'student',
        'student',
        'student',
        'instructor',
        'student',
        'student',
        'student',
        'student',
        'instructor',
        'student',
        'student',
        'student',
        'student',
        'instructor',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 25) as $index) {
            $firstName = $this->firstNames[rand(0, count($this->firstNames) - 1)];
            $lastName = $this->lastNames[rand(0, count($this->lastNames) - 1)];
            $name = sprintf('%s %s', $firstName, $lastName);
            $email = strtolower(sprintf("%s.%s%d@example.com", $firstName, $lastName, $index));

            $type = $this->types[rand(0, count($this->types) - 1)];

            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($name),
                'type' => $type,
                'user_role' => 'user',
                'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
                'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
            ]);

            DB::table('profiles')->insert([
                'age' => rand(11, 17),
                'user_id' => $userId,
                'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
                'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
            ]);
        }
    }
}
