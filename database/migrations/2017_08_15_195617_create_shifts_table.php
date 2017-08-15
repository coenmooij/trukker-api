<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_profile_id')->unsigned();
            $table->foreign('job_profile_id')->references('id')->on('job_profiles');
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('compensation')->unsigned();
            $table->string('start_date');
            $table->string('end_date');
            $table->string('start_location');
            $table->string('end_location');
            $table->boolean('is_retour')->default(false);
            $table->string('outbound_cargo');
            $table->string('inbound_cargo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
