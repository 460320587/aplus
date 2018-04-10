<?php

use Illuminate\Database\Seeder;
use Someline\Models\Foundation\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        User::create([
            'name' => "管理员",
            'email' => 'admin@someline.com',
            'password' => bcrypt('Abc12345'),
            'remember_token' => str_random(10),
            'gender' => 'M',
            'birthday' => '1994-07-08',
            'country' => 'CN',
            'timezone' => 'Asia/Shanghai',
            'locale' => 'en',
            'username' => 'admin',
            'phone_number' => '+1234567890',
            'status' => 1,
        ]);

        User::create([
            'name' => "审核员",
            'email' => 'reviewer@someline.com',
            'password' => bcrypt('Abc12345'),
            'remember_token' => str_random(10),
            'gender' => 'M',
            'birthday' => '1994-07-08',
            'country' => 'CN',
            'timezone' => 'Asia/Shanghai',
            'locale' => 'en',
            'username' => 'reviewer',
            'phone_number' => '+1234567890',
            'status' => 1,
        ]);

        User::create([
            'name' => "发布员",
            'email' => 'publisher@someline.com',
            'password' => bcrypt('Abc12345'),
            'remember_token' => str_random(10),
            'gender' => 'M',
            'birthday' => '1994-07-08',
            'country' => 'CN',
            'timezone' => 'Asia/Shanghai',
            'locale' => 'en',
            'username' => 'publisher',
            'phone_number' => '+1234567890',
            'status' => 1,
        ]);

        factory(User::class, 50)->create();
    }
}
