<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'adminuser',
            'email' => 'admin.votes@marketplaz.com',
            'password' => bcrypt('@admin.votes'),
        ]);
    }
}
