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
        Schema::create('clean_scrapes', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->string('otherfeatures', 255)->default("");
            $table->integer('mileage');
            $table->string('drivetype', 100)->default("automatic");
            $table->string('datetimeposted', 100);
            $table->string('trim', 100)->default("");
            $table->integer('price');
            $table->string('pageurl', 255);
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
        Schema::dropIfExists('clean_scrapes');
        Schema::dropColumn('year');
        Schema::dropColumn('brand');
        Schema::dropColumn('model');
        Schema::dropColumn('otherfeatures');
        Schema::dropColumn('mileage');
        Schema::dropColumn('drivetype');
        Schema::dropColumn('datetimeposted');
        Schema::dropColumn('trim');
        Schema::dropColumn('price');
        Schema::dropColumn('pageurl');
        Schema::dropColumn('uid');
    }
};
