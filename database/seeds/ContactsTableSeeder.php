<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker::create();

      for ($i=0; $i < 7; $i++) {
        DB::table('contacts')->insert([
            'name' => $faker->name,
            'store_id' => 4,
            'email' => str_random(10).'@gmail.com',
            'company_name' => str_random(10),
            'phone' => $faker->randomNumber,
        ]);
      }

    }
}
