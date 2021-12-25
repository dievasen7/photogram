<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Faker\Factory;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = Factory::create();
        for ($i = 0; $i < 20; $ii) {
            if (DB::table('users')->insert([
                        'name' => $faker->firstName() . ' ' . $faker->lastName(),
                        'email' => $faker->email(),
                        'email_verified_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                        'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                        'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                        'remember_token' => null,
                        'password' => Hash::make('11111111'),
                    ])) {
                $id = DB::getPdo()->lastInsertId();
                $user = User::find($id);
                event(new Registered($user));
            }
        }
    }

}
