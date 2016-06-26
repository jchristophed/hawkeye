<?php

use Illuminate\Database\Seeder;

class BlockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('block')->insert(['name' => 'Aile', 'residence_id' => 1]);
        DB::table('block')->insert(['name' => 'Bâtiment 31', 'residence_id' => 1]);
        DB::table('block')->insert(['name' => 'Bâtiment 35', 'residence_id' => 1]);
        DB::table('block')->insert(['name' => 'Bâtiment 37', 'residence_id' => 1]);
        DB::table('block')->insert(['name' => 'Principal', 'residence_id' => 3]);
    }
}
