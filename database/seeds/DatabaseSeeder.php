<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        \Auth::loginUsingId(1);
        $this->call(SomelineCategoriesTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
    }
}
