<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker::create();

      for ($i=0; $i < 13; $i++) {
        DB::table('users')->insert([
            'name' => $faker->name,
            'username' => str_random(1),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('123456'),
        ]);
      }
    }
}
