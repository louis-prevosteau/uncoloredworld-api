<?php

use App\Model\Personne;
use App\Model\Role;
use App\User;
use Illuminate\Database\Seeder;
use App\Model\Statistique;

class PersonneTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker\Factory::create('fr_FR');
        $personnes = factory(Personne::class, 10)->make()
            ->each(function ($personne) use ($faker) {
                $user = factory(User::class)->create([
                    'name' => $personne->prenom . ' ' . $personne->nom,
                    'email' => $personne->prenom . '.' . $personne->nom . '@' . $faker->randomElement(['domain.fr', 'gmail.com', 'hotmail.com', 'truc.com', 'machin.fr']),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
                $statistique = factory(Statistique::class)->create([
                    'score' => 0,
                    'high_score' => 0,
                    'tirs' => 0,
                    'ennemis_tues' => 0,
                    'morts' => 0,
                    'bonus' => 0,
                    'malus' => 0,
                ]);
                $user->role()->save(factory(Role::class)->make());
                $personne->user_id = $user->id;
                $personne->statistique_id = $statistique->id;
                $personne->save();
            });
        // Robert Duchmol : joueur
        $user  = factory(User::class)->create([
            'name' => 'Robert Duchmol',
            'email' => 'robert.duchmol@domain.fr',
            'password' => '$2y$10$UFYqX8c1aRFtvZ6AdlV17uesbirEwrRpCz1/fKmFZL2PXSyiHqoG2', // secret
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $statistique = factory(Statistique::class)->create([
            'score' => 0,
            'high_score' => 0,
            'tirs' => 0,
            'ennemis_tues' => 0,
            'morts' => 0,
            'bonus' => 0,
            'malus' => 0,
        ]);
        $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'admin']));
        $personne = factory(Personne::class)->make([
            'nom' => 'Duchmol',
            'prenom' => 'Robert',
            'pseudo' => 'RobiDuch',
            /*'score' => 1,
            'high_score' => 2,
            'tirs' => 3,
            'ennemis_tues' => 4,
            'morts' => 5,
            'bonus' => 6,
            'malus' => 7,*/

        ]);
        $personne->user_id = $user->id;
        $personne->statistique_id = $statistique->id;
        $personne->save();

        // Gollum : admin
        $user = factory(User::class)->create([
            'name' => 'Gollum',
            'email' => 'gollum@domain.fr',
            'password' => '$2y$10$UFYqX8c1aRFtvZ6AdlV17uesbirEwrRpCz1/fKmFZL2PXSyiHqoG2', // secret
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $statistique = factory(Statistique::class)->create([
            'score' => 2750,
            'high_score' => 132750,
            'tirs' => 120,
            'ennemis_tues' => 14,
            'morts' => 23,
            'bonus' => 8,
            'malus' => 2,
        ]);
        $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'admin']));
        $personne = factory(Personne::class)->make([
            'nom' => 'Smeagol',
            'prenom' => '',
            'pseudo' => 'Golum',
            'avatar' => 'avatars/gollum.jpeg',
            /*'score' => 0,
            'high_score' => 0,
            'tirs' => 0,
            'ennemis_tues' => 0,
            'morts' => 0,
            'bonus' => 0,
            'malus' => 0,*/
        ]);
        $personne->user_id = $user->id;
        $personne->statistique_id = $statistique->id;
        $personne->save();

        // Gérard Martin : auteur
        $user = factory(User::class)->create([
            'name' => 'Gérard Martin',
            'email' => 'gerard.martin@domain.fr',
            'password' => '$2y$10$UFYqX8c1aRFtvZ6AdlV17uesbirEwrRpCz1/fKmFZL2PXSyiHqoG2', // secret
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $statistique = factory(Statistique::class)->create([
            'score' => 0,
            'high_score' => 0,
            'tirs' => 0,
            'ennemis_tues' => 0,
            'morts' => 0,
            'bonus' => 0,
            'malus' => 0,
        ]);
        $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'auteur']));
        $personne = factory(Personne::class)->make([
            'nom' => 'Martin',
            'prenom' => 'Gérard',
            'pseudo' => 'GéMar',
            /*'score' => 1,
            'high_score' => 1,
            'tirs' => 1,
            'ennemis_tues' => 1,
            'morts' => 1,
            'bonus' => 1,
            'malus' => 1,*/
        ]);
        $personne->user_id = $user->id;
        $personne->statistique_id = $statistique->id;
        $personne->save();
    }
}
