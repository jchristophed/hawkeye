<?php

use Illuminate\Database\Seeder;

class DocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document')->insert(['name' => 'Photocopie CNI', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'Photocopie carte de séjour', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'RIB', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'Photocopie avis d\'imposition', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'Autorisation prélèvement SEPA', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'Attestation assurance risques locatifs', 'class' => 'general']);
        DB::table('document')->insert(['name' => 'Certificat scolarité', 'class' => 'student']);
        DB::table('document')->insert(['name' => 'Photocopie certificat inscription', 'class' => 'student']);
        DB::table('document')->insert(['name' => 'Convention de stage', 'class' => 'trainee']);
        DB::table('document')->insert(['name' => 'Contrat de travail', 'class' => 'employe']);
        DB::table('document')->insert(['name' => 'Bulletins de salaire', 'class' => 'employe']);
        DB::table('document')->insert(['name' => 'Justificatifs autres ressources', 'class' => 'employe']);
        DB::table('document')->insert(['name' => 'Quittance de loyer', 'class' => 'employe']);
        DB::table('document')->insert(['name' => 'Taxe foncière', 'class' => 'employe']);
        DB::table('document')->insert(['name' => 'Engagement de caution', 'class' => 'guarantor']);
        DB::table('document')->insert(['name' => 'Derniers justificatifs revenus', 'class' => 'guarantor']);
        DB::table('document')->insert(['name' => 'Photocopie CNI', 'class' => 'guarantor']);
        DB::table('document')->insert(['name' => 'Photocopie avis d\'imposition', 'class' => 'guarantor']);
        DB::table('document')->insert(['name' => 'Justificatif de résidence', 'class' => 'guarantor']);
        DB::table('document')->insert(['name' => 'Imprimé demande LOCAPASS', 'class' => 'locapass']);

    }
}
