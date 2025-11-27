<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
  // Cấu hình vest đã lưu (Saved Configurations)
  Schema::create('suit_configurations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('suit_model_id')->constrained()->onDelete('cascade');
      $table->foreignId('fabric_id')->constrained()->onDelete('cascade');
      $table->string('share_code')->unique();
      $table->json('selected_options');
      $table->decimal('total_price', 12, 0);
      $table->string('screenshot')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->index('share_code');
  });

  // Giỏ hàng (Carts)
  Schema::create('carts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
      $table->string('session_id')->nullable();
      $table->timestamps();

      $table->index(['user_id', 'session_id']);
  });

  // Chi tiết giỏ hàng (Cart Items)
  Schema::create('cart_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('cart_id')->constrained()->onDelete('cascade');
      $table->foreignId('suit_configuration_id')->constrained()->onDelete('cascade');
      $table->integer('quantity')->default(1);
      $table->json('measurements')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
  });

  // Đơn hàng (Orders)
  Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
      $table->string('order_number')->unique();
      $table->string('customer_name');
      $table->string('customer_email');
      $table->string('customer_phone');
      $table->text('shipping_address');
      $table->text('billing_address')->nullable();
      $table->string('city')->nullable();
      $table->string('district')->nullable();
      $table->string('ward')->nullable();
      $table->decimal('subtotal', 12, 0);
      $table->decimal('shipping_fee', 12, 0)->default(0);
      $table->decimal('discount', 12, 0)->default(0);
      $table->string('discount_code')->nullable();
      $table->decimal('total', 12, 0);
      $table->string('payment_method'); // momo, vnpay, bank_transfer, cod
      $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
      $table->string('order_status')->default('pending'); // pending, confirmed, processing, tailoring, shipping, delivered, cancelled
      $table->json('payment_data')->nullable();
      $table->text('admin_notes')->nullable();
      $table->text('customer_notes')->nullable();
      $table->timestamp('paid_at')->nullable();
      $table->timestamp('confirmed_at')->nullable();
      $table->timestamp('shipped_at')->nullable();
      $table->timestamp('delivered_at')->nullable();
      $table->timestamps();

      $table->index('order_number');
      $table->index('order_status');
  });

  // Chi tiết đơn hàng (Order Items)
  Schema::create('order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->onDelete('cascade');
      $table->foreignId('suit_configuration_id')->nullable()->constrained()->onDelete('set null');
      $table->string('suit_model_name');
      $table->string('fabric_name');
      $table->json('selected_options');
      $table->json('measurements')->nullable();
      $table->decimal('unit_price', 12, 0);
      $table->integer('quantity');
      $table->decimal('total_price', 12, 0);
      $table->string('screenshot')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
  });

  // Mã giảm giá (Discount Codes)
  Schema::create('discount_codes', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('description')->nullable();
      $table->string('type'); // percentage, fixed
      $table->decimal('value', 12, 2);
      $table->decimal('min_order_value', 12, 0)->nullable();
      $table->decimal('max_discount', 12, 0)->nullable();
      $table->integer('usage_limit')->nullable();
      $table->integer('usage_count')->default(0);
      $table->timestamp('starts_at')->nullable();
      $table->timestamp('expires_at')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
  });
  }

  public function down(): void
  {
  Schema::dropIfExists('discount_codes');
  Schema::dropIfExists('order_items');
  Schema::dropIfExists('orders');
  Schema::dropIfExists('cart_items');
  Schema::dropIfExists('carts');
  Schema::dropIfExists('suit_configurations');
  }
};
