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
        Schema::create('scrape_cycles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('ScrapedAt')->useCurrent();
            $table->integer('TotalNewCarsAdded');
            $table->boolean('error')->default(false); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrape_cycles');
    }
};
