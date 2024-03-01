<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentCategoryId = DB::table('categories')->insertGetId([
            'name' => 'Puttaus',
            'is_active' => true,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('categories')->insertGetId([
            'name' => 'Puttiviesti',
            'is_active' => true,
            'parent_category_id' => $parentCategoryId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);


        $parentCategoryId = DB::table('categories')->insertGetId([
            'name' => 'Lämmittely',
            'is_active' => true,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('categories')->insertGetId([
            'name' => 'Puttiviesti',
            'is_active' => true,
            'parent_category_id' => $parentCategoryId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        $parentCategoryId = DB::table('categories')->insertGetId([
            'name' => 'Heittäminen',
            'is_active' => true,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('categories')->insertGetId([
            'name' => 'Rystyheiton perusteet',
            'is_active' => true,
            'parent_category_id' => $parentCategoryId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);

        DB::table('categories')->insertGetId([
            'name' => 'Kämmenheiton perusteet',
            'is_active' => true,
            'parent_category_id' => $parentCategoryId,
            'created_at' => DB::raw('CURRENT_TIMESTAMP()'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP()'),
        ]);
    }
}
