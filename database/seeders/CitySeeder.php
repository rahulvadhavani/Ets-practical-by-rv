<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        $json = \Illuminate\Support\Facades\File::get("database/data/cities.json");
		$cities = json_decode($json,1);
		$chunks = array_chunk($cities, 500);
		foreach ($chunks as $chunk) {
			DB::table('cities')->insert($chunk);
		}
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
