<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItemsTableSeeder extends Seeder
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
        DB::table('items')->insert([
            'name' => $faker->name,
            'price' => $faker->randomNumber,
        ]);
      }
    }
}
