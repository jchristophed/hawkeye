<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ResidenceTableSeeder::class);
        $this->call(FlatTableSeeder::class);
        $this->call(TenantTableSeeder::class);
        $this->call(ContractTableSeeder::class);
        $this->call(DocumentTableSeeder::class);
        $this->call(BlockTableSeeder::class);
        $this->call(ViewTableSeeder::class);
    }
}
