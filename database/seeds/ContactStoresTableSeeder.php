<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactStoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker::create();

      for ($i=0; $i < 4; $i++) {
        DB::table('contacts')->insert([
            'name' => $faker->name,
            'email' => str_random(10).'@gmail.com',
            'company_name' => str_random(10),
            'phone' => $faker->randomNumber,
        ]);
    }
}
