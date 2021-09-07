<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->unsignedInteger('service')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('category');
            $table->unsignedInteger('rate');
            $table->unsignedInteger('min');
            $table->unsignedInteger('max');
            $table->boolean('dripfeed');
            $table->unsignedInteger('average_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}
