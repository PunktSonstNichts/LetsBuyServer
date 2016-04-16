<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shoppinglist_id_1')->unsigned();
            $table->integer('shoppinglist_id_2')->unsigned();
            $table->enum('states_1', ['accepted', 'rejected']);
            $table->enum('states_2', ['accepted', 'rejected']);
            $table->timestamp('change_1');
            $table->timestamp('change_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('match');
    }
}
