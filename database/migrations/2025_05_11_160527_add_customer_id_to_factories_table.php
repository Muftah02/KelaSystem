<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // في ملف الـ migration الجديد
    public function up()
    {
        Schema::table('factories', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factories', function (Blueprint $table) {
            //
        });
    }
};
