<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledEmailsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create(
            'scheduled_emails',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('to_name');
                $table->string('to_email');
                $table->string('subject');
                $table->longText('body');
                $table->dateTime('deliver_at')->nullable();
                $table->string('status');
                $table->timestamps();
                $table->index(['deliver_at', 'status']);
            }
        );
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_emails');
    }
}
