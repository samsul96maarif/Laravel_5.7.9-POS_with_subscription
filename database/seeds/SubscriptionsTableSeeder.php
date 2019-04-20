<?php

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('subscriptions')->insert([
          'name' => 'Hobbyist Plan',
          'price' => 140000,
          'num_items' => 50,
          'num_invoices' => 10,
          'num_users' => 10,
          'writer_id' => 2,
          'action' => 'create'
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'Freelancer Plan',
          'price' => 285000,
          'num_items' => 50,
          'num_invoices' => 15,
          'num_users' => 15,
          'writer_id' => 2,
          'action' => 'create'
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'Studio Plan',
          'price' => 435000,
          'num_items' => 50,
          'num_invoices' => 20,
          'num_users' => 20,
          'writer_id' => 2,
          'action' => 'create'
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'Agency Plan',
          'price' => 885000,
          'num_items' => 50,
          'num_invoices' => 25,
          'num_users' => 25,
          'writer_id' => 2,
          'action' => 'create'
      ]);
      DB::table('subscriptions')->insert([
          'name' => 'Master',
          'price' => 999999,
          'num_items' => 0,
          'num_invoices' => 0,
          'num_users' => 0,
          'writer_id' => 2,
          'action' => 'create'
      ]);
    }
}
