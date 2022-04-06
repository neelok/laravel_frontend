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
        Schema::create('scrapes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('pictureurl', 2048)->default('');
            $table->string('pageurl', 2048)->default('');
            $table->string('price', 255);
            $table->string('title', 255);
            $table->text('description');
            $table->string('distance', 255)->default('');
            $table->string('kms', 255)->deafult('');
            $table->string('location', 255)->deafult('');
            $table->string('uid', 255);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrapes');
    }
};
