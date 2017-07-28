<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXorUsersClientsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create(
            'xor_users_clients',
            function (Blueprint $table) {
                $table->increments('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('client_id')->references('id')->on('clients');
                $table->timestamps();
                $table->index(['user_id', 'client_id']);
            }
        );
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xor_users_clients');
    }
}
