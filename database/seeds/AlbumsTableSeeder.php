<?php

use Illuminate\Database\Seeder;
use Someline\Models\Foundation\User;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('albums')->truncate();
        Schema::enableForeignKeyConstraints();

        \Someline\Models\Foundation\Album::create([
            'book_id' => "111",
            'name' => "陪你度过漫长岁月",
            'author' => "陈",
            'brief' => "陪你度过漫长岁月陪你度过漫长岁月陪你度过漫长岁月",
            'broadcaster' => "我那个",
            'broadcaster_type' => "女单播",
            'copyright' => "米赢",
            'keywords' => "武侠,天神",
            'payment_type' => 1,
            'payment_amount' => "1.00",
            'someline_image_id' => 1,
            'status' => 0,
        ]);

    }
}
