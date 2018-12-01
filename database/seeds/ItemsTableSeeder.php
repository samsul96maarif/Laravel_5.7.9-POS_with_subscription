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
            'name' => str_random(7),
            'description' => 'blablabla',
            'organization_id' => 2,
            'price' => $faker->randomNumber.'000',
            'stock' => $faker->randomNumber,
        ]);
      }
    }
}
