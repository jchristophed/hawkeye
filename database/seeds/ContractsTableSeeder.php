<?php

use Illuminate\Database\Seeder;

class ContractTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0 ; $i<100 ; $i++) {
            DB::table('contract')->insert(['start_date' => '2015-' . rand(1,12) . '-' . rand(1,30), 'end_date' => '2016-' . rand(1,12) . '-' . rand(1,30), 'price' => rand(400, 800), 'application_fee' => rand(20, 150), 'deposit' => rand(50, 100), 'mode_of_payment' => 'Carte de crédit', 'flat_id' => rand(5, 60), 'tenant_id' => rand(1, 60)]);
        }
    }
}
