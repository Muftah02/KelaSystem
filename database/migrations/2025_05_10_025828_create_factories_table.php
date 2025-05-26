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
        Schema::create('factories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name'); // الاسم التجاري
            $table->enum('machine_type', ['آلي', 'نصف آلي', 'يدوي']); // نوع الآلة
            $table->enum('reservation_type', ['شهري', 'سنوي']); // نوع الحجز
            $table->decimal('factory_area', 8, 2); // مساحة المصنع (رقم عشري)
            $table->decimal('specified_ton_quantity', 8, 2); // كمية الطن المحددة
            $table->string('map_location'); // رابط الموقع على Google Maps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factories');
    }
};
