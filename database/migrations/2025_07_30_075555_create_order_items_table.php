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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->string('product_name'); // نسخة من اسم المنتج وقت الطلب
            $table->string('product_sku'); // نسخة من SKU وقت الطلب
            $table->decimal('unit_price', 10, 2); // السعر وقت الطلب
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // الإجمالي لهذا العنصر
            
            // معلومات المنتج وقت الطلب
            $table->json('product_attributes')->nullable(); // اللون، الحجم، إلخ
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};