<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('id_card')->unique();
            $table->string('specialty')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('nationality')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->date('birth_date')->nullable(false);
            $table->string('id_card')->unique()->nullable(false);
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('price')->nullable(false)->default('$0.00');
            $table->string('periodicity')->nullable(false);
            $table->string('description')->nullable(true);
            $table->timestamps();
        });
        Schema::create('plan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained('plans')
                ->cascadeOnDelete();
            $table->string('detail');
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->timestamps();
        });
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();
            $table->string('item');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->timestamps();
        });
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->string('type')->nullable(false);
            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->cascadeOnDelete();
            $table->foreignId('doctor_id')
                ->nullable(false)
                ->constrained('doctors')
                ->cascadeOnDelete();
            $table->foreignId('patient_id')
                ->nullable(false)
                ->constrained('patients')
                ->cascadeOnDelete();
            $table->longText('content');
            $table->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id');
            $table->string('action', 50);
            $table->json('changes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->string('meta_type')->nullable(false);
            $table->bigInteger('meta_id')->unsigned()->nullable(false);
            $table->string('meta_key')->nullable(false);
            $table->longText('meta_value')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('plan_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('metadata');
    }
};
