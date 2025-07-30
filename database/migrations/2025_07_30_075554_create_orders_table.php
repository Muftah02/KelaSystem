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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->comment('المستخدم الذي أنشأ الطلب');
            
            // حالة الطلب
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'delivered', 
                'cancelled', 'refunded', 'on_hold'
            ])->default('pending');
            
            // المبالغ المالية
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // طريقة الدفع
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'check'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            
            // معلومات الشحن والعنوان
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->string('shipping_method')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // معلومات إضافية
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};