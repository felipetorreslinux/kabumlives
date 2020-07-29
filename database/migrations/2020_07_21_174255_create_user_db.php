<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_db', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('login');
            $table->string('nome');
            $table->string('senha');
            $table->text('token');
            $table->integer('status');
            $table->integer('logado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_db');
    }
}
