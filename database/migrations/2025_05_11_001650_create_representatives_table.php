<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('representatives', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')
              ->constrained('customers')
              ->onDelete('cascade')->nullable();
        $table->string('full_name')->nullable();
        $table->string('national_id')->nullable();
        $table->string('phone')->nullable();
        $table->string('city')->nullable();
        $table->text('address')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representatives');
    }
};
