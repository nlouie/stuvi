<?php

use Illuminate\Database\Seeder;
use App\User;
use App\University;
// use Faker\Factory;

class UserTableSeeder extends Seeder {

public function run()
{
    DB::table('users')->delete();

    $bu = University::where('email_suffix', '=', 'bu.edu')->get()->first();

    User::create([
        'email'         => 'luoty@bu.edu',
        'password'      => bcrypt('123456'),
        'phone_number'  => '8572064789',
        'first_name'    => 'Tianyou',
        'last_name'     => 'Luo'

    ]);

    User::create([
        'email'         => 'test@bu.edu',
        'password'      => bcrypt('123456'),
        'first_name'    => 'Pengcheng',
        'last_name'     => 'Ding'

    ]);

    User::create([
        'email'         => 'seller@stuvi.com',
        'password'      => bcrypt('123456'),
        'university_id' => $bu->id
    ]);

    User::create([
        'email'     => 'buyer@stuvi.com',
        'password'  => bcrypt('123456'),
        'university_id' =>  $bu->id
    ]);

    // $faker = Factory::create();
    //
    // for ($i = 0; $i < 10; $i++)
    // {
    //     $user = User::create(array(
    //         'email' => $faker->email,
    //         'password' => bcrypt($faker->word),
    //         'phone_number' => $faker->phoneNumber,
    //         'first_name' => $faker->firstName,
    //         'last_name' => $faker->lastName
    //     ));
    // }
}

}
