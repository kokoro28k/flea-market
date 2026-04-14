<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImagePathToTextInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('items', function (Blueprint $table) {
              $table->dropColumn('image_path');
         });

         Schema::table('items', function (Blueprint $table) {
             $table->text('image_path')->after('price');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('items', function (Blueprint $table) {
             $table->dropColumn('image_path');
         });

         Schema::table('items', function (Blueprint $table) {
             $table->string('image_path')->after('price');
         });

    }
}
