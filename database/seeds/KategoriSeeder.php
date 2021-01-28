<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Wortel',
            'kategori' => 1
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Salad',
            'kategori' => 5
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Beras Merah',
            'kategori' => 6
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Beras Putih',
            'kategori' => 6
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Pupuk',
            'kategori' => 7
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Vitamin',
            'kategori' => 7
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Lain Lain',
            'kategori' => 8
        ]); 
    
        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Tomat',
            'kategori' => 1
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Apel',
            'kategori' => 2
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Lemon',
            'kategori' => 2
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Oat',
            'kategori' => 3
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Kacang',
            'kategori' => 3
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Yoghurt',
            'kategori' => 4
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Jamu',
            'kategori' => 4
        ]); 

        DB::table('kategoris')->insert([
            'id' => Uuid::generate()->string,
            'sub_kategori' => 'Biskuit',
            'kategori' => 5
        ]); 
    }
}
