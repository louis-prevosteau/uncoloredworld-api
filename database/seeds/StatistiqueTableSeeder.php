<?php

use App\Model\Statistique;
use Illuminate\Database\Seeder;

class StatistiqueTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Statistique::class, 9)->create();

        $statistique = factory(Statistique::class)->create([
            'score' => 0,
            'high_score' => 0,
            'tirs' => 0,
            'ennemis_tues' => 0,
            'morts' => 0,
            'bonus' => 0,
            'malus' => 0,
        ]);
    }
}
