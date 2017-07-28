<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('password', 60);
                $table->string('salt')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('token')->nullable();
                $table->dateTime('token_expires')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
