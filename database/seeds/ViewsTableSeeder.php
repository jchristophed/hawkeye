<?php

use Illuminate\Database\Seeder;

class ViewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('view')->insert(['name' => 'Jardin', 'residence_id' => 1]);
        DB::table('view')->insert(['name' => 'Rue', 'residence_id' => 1]);
        DB::table('view')->insert(['name' => 'Toits', 'residence_id' => 1]);
        DB::table('view')->insert(['name' => 'Cour intérieure', 'residence_id' => 3]);
        DB::table('view')->insert(['name' => 'Rue', 'residence_id' => 3]);
    }
}
