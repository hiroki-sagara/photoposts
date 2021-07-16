<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'category' => '建物',
        ]);
        DB::table('categories')->insert([
            'category' => '料理',
        ]);
        DB::table('categories')->insert([
            'category' => 'コレクション',
        ]);
        DB::table('categories')->insert([
            'category' => '動物',
        ]);
        DB::table('categories')->insert([
            'category' => '景色',
        ]);
        DB::table('categories')->insert([
            'category' => '日常の一コマ',
        ]);
        DB::table('categories')->insert([
            'category' => 'その他',
        ]);
    }
}
