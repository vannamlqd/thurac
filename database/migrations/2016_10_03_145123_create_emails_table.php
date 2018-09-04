<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('domain_id');
            $table->integer('ip_id');
            $table->integer('collect_id')->nullable();
            $table->integer('time_create')->nullable();
            $table->string('email');
            $table->string('country_code')->nullable();
            $table->string('ip');
            $table->string('domain');
            $table->string('to');
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->string('public')->default(1);
            $table->integer('status');
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
        Schema::dropIfExists('emails');
    }
}
