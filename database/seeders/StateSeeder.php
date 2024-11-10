<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('states')->truncate();
		$json = \Illuminate\Support\Facades\File::get("database/data/states.json");
		$states = json_decode($json,1);
		$chunks = array_chunk($states, 100);
		foreach ($chunks as $chunk) {
			DB::table('states')->insert($chunk);
		}
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
