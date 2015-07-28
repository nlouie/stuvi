<?php

use App\Email;
use App\University;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

// use Faker\Factory;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        $bu = University::where('email_suffix', '=', 'bu.edu')->get()->first();

        $user = User::create([
            'password'        => bcrypt('123456'),
            'phone_number'    => '8572064789',
            'first_name'      => 'Tianyou',
            'last_name'       => 'Luo',
            'university_id'   => $bu->id,
            'role'            => 'uac',
        ]);
        $email = Email::create([
            'user_id'       => $user->id,
            'email_address' => 'luoty@bu.edu',
            'verified'       => true,
            'verification_code' => \App\Helpers\generateRandomCode(Config::get('user.verification_code_length')),
        ]);
        $user->setPrimaryEmail($email->id);

        $user = User::create([
            'password'        => bcrypt('123456'),
            'first_name'      => 'Pengcheng',
            'last_name'       => 'Ding',
            'university_id'   => $bu->id,
            'role'            => 'uac',
        ]);
        $email = Email::create([
            'user_id'       => $user->id,
            'email_address' => 'test@bu.edu',
            'verified'       => true,
            'verification_code' => \App\Helpers\generateRandomCode(Config::get('user.verification_code_length')),
        ]);
        $user->setPrimaryEmail($email->id);

        $user = User::create([
            'password'        => bcrypt('123456'),
            'first_name'      => 'Seller',
            'last_name'       => 'Stuvi',
            'university_id'   => $bu->id,
            'role'            => 'ua',
        ]);
        $email = Email::create([
            'user_id'       => $user->id,
            'email_address' => 'seller@bu.edu',
            'verified'       => true,
            'verification_code' => \App\Helpers\generateRandomCode(Config::get('user.verification_code_length')),
        ]);
        $user->setPrimaryEmail($email->id);

        $user = User::create([
            'password'        => bcrypt('123456'),
            'first_name'      => 'Buyer',
            'last_name'       => 'Stuvi',
            'university_id'   => $bu->id,
            'role'            => 'ua',
        ]);
        $email = Email::create([
            'user_id'       => $user->id,
            'email_address' => 'buyer@bu.edu',
            'verified'       => true,
            'verification_code' => \App\Helpers\generateRandomCode(Config::get('user.verification_code_length')),
        ]);
        $user->setPrimaryEmail($email->id);

        $user = User::create([
            'password'        => bcrypt('123456'),
            'first_name'      => 'Courier',
            'last_name'       => 'Stuvi',
            'university_id'   => $bu->id,
            'role'            => 'ac',
        ]);
        $email = Email::create([
            'user_id'       => $user->id,
            'email_address' => 'courier@bu.edu',
            'verified'       => true,
            'verification_code' => \App\Helpers\generateRandomCode(Config::get('user.verification_code_length')),
        ]);
        $user->setPrimaryEmail($email->id);

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
