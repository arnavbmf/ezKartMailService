<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maillogs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->length(11)->nullable();
            $table->string('to_emailId')->nullable();
            $table->string('from_emailId')->nullable();
            $table->string('subject')->nullable();
            $table->text('mail_body')->nullable();
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
        Schema::dropIfExists('maillogs');
    }
};
