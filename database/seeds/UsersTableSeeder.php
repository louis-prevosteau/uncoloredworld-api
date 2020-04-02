<?php

use App\Model\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(App\User::class, 3)->create()
            ->each(function ($user) {
                $user->role()->save(factory(Role::class)->make());
            });

        $user = factory(App\User::class)->create([
            'name' => 'Robert Duchmol',
            'email' => 'robert.duchmol@domain.fr',
            'email_verified_at' => now(),
            'password' => '$2y$10$DjPwcE/APOvpnc6QKSlaNuZ0pKeKS2gysCrCWEcTx9uZzZu8x66UG', //secret
            'remember_token' => Str::random(10),
        ]);

        $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'admin']));
    }
}
