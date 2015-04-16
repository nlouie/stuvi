<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory;

class UserTableSeeder extends Seeder {

public function run()
{
    DB::table('users')->delete();

    User::create([
        'username' => 'test',
        'email' => 'test@bu.edu',
        'password' => bcrypt('123456')
    ]);

    $faker = Factory::create();

    for ($i = 0; $i < 10; $i++)
    {
        $user = User::create(array(
            'username' => $faker->userName,
            'email' => $faker->email,
            'password' => bcrypt($faker->word),
            'phone_number' => $faker->phoneNumber,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName
        ));
    }
}

}