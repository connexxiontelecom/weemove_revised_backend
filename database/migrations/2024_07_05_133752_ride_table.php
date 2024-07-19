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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->nullable(false);
            $table->string('ride_id')->nullable(false)->unique();
            $table->bigInteger('passenger_id')->nullable(false);
            $table->string('pickup')->nullable(false);
            $table->string('destination')->nullable(false);
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('rating')->nullable();
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
        Schema::dropIfExists('rides');
    }
};
