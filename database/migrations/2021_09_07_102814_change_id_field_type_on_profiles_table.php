<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdFieldTypeOnProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Drop foreign
        Schema::table('orders', static function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
        });

        // Change id type
        Schema::table('profiles', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        // Change it on orders table and set foreign again
        Schema::table('orders', static function (Blueprint $table) {
            $table->unsignedBigInteger('profile_id')->change();
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop foreign
        Schema::table('orders', static function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
        });

        // Change id type
        Schema::table('profiles', static function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });

        // Change it on orders table and set foreign again
        Schema::table('orders', static function (Blueprint $table) {
            $table->unsignedInteger('profile_id')->change();
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }
}
