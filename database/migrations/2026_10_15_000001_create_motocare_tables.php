<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('users',fn(Blueprint $t)=>$t->string('role')->default('customer')->after('password'));
  Schema::create('motor_brands',fn(Blueprint $t)=>[$t->id(),$t->string('name')->unique(),$t->timestamps()]);
  Schema::create('motor_models',fn(Blueprint $t)=>[$t->id(),$t->foreignId('motor_brand_id')->constrained()->cascadeOnDelete(),$t->string('name'),$t->timestamps(),$t->unique(['motor_brand_id','name'])]);
  Schema::create('customer_motors',fn(Blueprint $t)=>[$t->id(),$t->foreignId('user_id')->constrained()->cascadeOnDelete(),$t->foreignId('motor_brand_id')->constrained()->cascadeOnDelete(),$t->foreignId('motor_model_id')->constrained()->cascadeOnDelete(),$t->string('plate_number'),$t->string('year')->nullable(),$t->string('color')->nullable(),$t->timestamps()]);
  Schema::create('mechanics',fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->string('phone')->nullable(),$t->string('specialization')->nullable(),$t->string('status')->default('active'),$t->string('photo')->nullable(),$t->timestamps()]);
  Schema::create('service_categories',fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->text('description')->nullable(),$t->timestamps()]);
  Schema::create('services',fn(Blueprint $t)=>[$t->id(),$t->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete(),$t->string('name'),$t->text('description')->nullable(),$t->decimal('price',14,2)->default(0),$t->unsignedInteger('duration_minutes')->default(60),$t->boolean('is_active')->default(true),$t->timestamps()]);
  Schema::create('service_packages',fn(Blueprint $t)=>[$t->id(),$t->foreignId('service_id')->nullable()->constrained()->nullOnDelete(),$t->string('name'),$t->text('description')->nullable(),$t->decimal('price',14,2)->default(0),$t->unsignedInteger('duration_minutes')->default(60),$t->boolean('is_active')->default(true),$t->timestamps()]);
  Schema::create('sparepart_categories',fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->text('description')->nullable(),$t->timestamps()]);
  Schema::create('spareparts',fn(Blueprint $t)=>[$t->id(),$t->foreignId('sparepart_category_id')->nullable()->constrained()->nullOnDelete(),$t->string('name'),$t->string('sku')->unique(),$t->text('description')->nullable(),$t->decimal('price',14,2),$t->unsignedInteger('stock')->default(0),$t->string('image')->nullable(),$t->boolean('is_active')->default(true),$t->timestamps()]);
  Schema::create('bookings',fn(Blueprint $t)=>[$t->id(),$t->foreignId('user_id')->constrained()->cascadeOnDelete(),$t->foreignId('customer_motor_id')->constrained()->cascadeOnDelete(),$t->foreignId('mechanic_id')->nullable()->constrained()->nullOnDelete(),$t->foreignId('service_package_id')->constrained()->cascadeOnDelete(),$t->date('booking_date'),$t->time('booking_time'),$t->text('complaint'),$t->decimal('estimated_price',14,2)->default(0),$t->string('status')->default('pending'),$t->timestamps()]);
  Schema::create('service_progress',fn(Blueprint $t)=>[$t->id(),$t->foreignId('booking_id')->constrained()->cascadeOnDelete(),$t->string('status'),$t->string('title'),$t->text('description')->nullable(),$t->unsignedTinyInteger('progress_percent')->default(0),$t->timestamps()]);
  Schema::create('orders',fn(Blueprint $t)=>[$t->id(),$t->foreignId('user_id')->constrained()->cascadeOnDelete(),$t->string('order_number')->unique(),$t->decimal('total_amount',14,2),$t->string('status')->default('pending'),$t->timestamps()]);
  Schema::create('order_items',fn(Blueprint $t)=>[$t->id(),$t->foreignId('order_id')->constrained()->cascadeOnDelete(),$t->foreignId('sparepart_id')->constrained()->restrictOnDelete(),$t->string('name'),$t->unsignedInteger('quantity'),$t->decimal('price',14,2),$t->decimal('subtotal',14,2),$t->timestamps()]);
  Schema::create('payments',fn(Blueprint $t)=>[$t->id(),$t->foreignId('order_id')->constrained()->cascadeOnDelete(),$t->string('method'),$t->decimal('amount',14,2),$t->string('status')->default('pending'),$t->timestamp('paid_at')->nullable(),$t->string('reference')->nullable(),$t->timestamps()]);
  Schema::create('invoices',fn(Blueprint $t)=>[$t->id(),$t->foreignId('order_id')->constrained()->cascadeOnDelete(),$t->string('invoice_number')->unique(),$t->timestamp('issued_at'),$t->timestamps()]);
  Schema::create('articles',fn(Blueprint $t)=>[$t->id(),$t->string('title'),$t->string('slug')->unique(),$t->string('excerpt')->nullable(),$t->longText('content'),$t->string('image')->nullable(),$t->timestamp('published_at')->nullable(),$t->boolean('is_published')->default(false),$t->timestamps()]);
 }
 public function down(): void {
  foreach(['articles','invoices','payments','order_items','orders','service_progress','bookings','spareparts','sparepart_categories','service_packages','services','service_categories','mechanics','customer_motors','motor_models','motor_brands'] as $t) Schema::dropIfExists($t);
  Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('role'));
 }
};
