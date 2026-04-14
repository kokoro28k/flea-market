<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     //condition:
        //1:良好
        //2:目立った傷や汚れなし
        //3:やや傷や汚れあり   
        //4:状態が悪い
    public function run()
    {
        $item=[
            'user_id' => 1,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => 1,
            'price' => '15000',
            'image_path' => 'images/clock.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

       

        $item=[
            'user_id' => 1,
            'name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => 2,
            'price' => '5000',
            'image_path' => 'images/hdd.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition' => 3,
            'price' => '300',
            'image_path' => 'images/onion.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => '革靴',
            'brand' => '',
            'description' => 'クラシックなデザインの革靴',
            'condition' => 4,
            'price' => '4000',
            'image_path' => 'images/shoes.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => 'ノートPC',
            'brand' => '',
            'description' => 'ク高性能なノートパソコン',
            'condition' => 1,
            'price' => '45000',
            'image_path' => 'images/pc.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => 'マイク',
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'condition' => 2,
            'price' => '8000',
            'image_path' => 'images/mic.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'brand' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 3,
            'price' => '3500',
            'image_path' => 'images/bag.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => 'タンブラー',
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'condition' => 4,
            'price' => '500',
            'image_path' => 'images/tumbler.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);
        
        $item=[
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'condition' => 1,
            'price' => '4000',
            'image_path' => 'images/coffeemill.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);

        $item=[
            'user_id' => 1,
            'name' => 'メイクセット',
            'brand' => '',
            'description' => '便利なメイクアップセット',
            'condition' => 2,
            'price' => '2500',
            'image_path' => 'images/makeupset.jpg',
            'status' => 0,        ];
        DB::table('items') ->insert ($item);
        }

}