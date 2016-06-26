<?php

use Illuminate\Database\Seeder;

class ResidenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('residence')->insert(['id' => 1, 'name' => 'Marengo']);
        DB::table('residence')->insert(['id' => 2, 'name' => 'Perrin-Solliers']);
        DB::table('residence')->insert(['id' => 3, 'name' => 'Puget']);
    }
}
