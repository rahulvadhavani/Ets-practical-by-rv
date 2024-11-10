<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->truncate();
        $countries = [
            ['code' => 'IN', 'name' => "India"],
        ];

        // Insert in chunks to avoid memory issues
        $chunks = array_chunk($countries, 100);

        foreach ($chunks as $chunk) {
            DB::table('countries')->insert($chunk);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
