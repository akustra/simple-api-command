<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();

            // hash
            // md5("dt" + "city_name") -> jeden klucz md5
            // select * from forecasts where city_name = "Wrocław"
            // group by hash
            $table->string('hash')->index();

            // klucz złożony
            $table->unsignedInteger('dt');
            $table->string('city_name', 512);

            // select * from forecasts where city_name = "Wrocław"
            // group by dt, city_name

            $table->float('temp_day');
            $table->float('temp_night');

            $table->string('description')->nullable();

            $table->float('pressure');

            $table->float('wind_speed');
            $table->float('wind_direction');

            $table->float('precipitation')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
