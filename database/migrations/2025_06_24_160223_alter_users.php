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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number',20)->nullable();
            $table->string('city',100)->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('address',200)->nullable();
            $table->integer('is_active')->default(1);
            $table->text('imageUrl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
            $table->dropColumn('address');
            $table->dropColumn('is_active');
            $table->dropColumn('imageUrl');
        });
    }
};
