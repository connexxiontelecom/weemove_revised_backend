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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable(false);
            $table->bigInteger('driver_id')->nullable(false);
            $table->string('brand')->nullable(false);
            $table->string('plate_number')->nullable(false);
            $table->string('year')->default(false);
            $table->string('colour')->nullable(false);
            $table->string('picture')->nullable(false);
            $table->integer('seats')->nullable();
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
        Schema::dropIfExists('vehicles');

    }
};


