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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable(false);
            $table->string('national_identification_number')->nullable(false);
            $table->string('license')->nullable(false);
            $table->string('id_document')->nullable(false);
            $table->boolean('license_verified')->nullable(false)->default(false);
            $table->boolean('verified')->default(false)->nullable(false);
            $table->bigInteger('vehicle_id')->nullable();
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
        Schema::dropIfExists('drivers');
    }
};
