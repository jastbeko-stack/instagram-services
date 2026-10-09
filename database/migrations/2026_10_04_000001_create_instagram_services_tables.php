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
        // 1. Update users table with balance, role, and contact
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->decimal('balance', 12, 2)->default(0.00)->after('is_admin');
            $table->string('phone')->nullable()->after('balance');
            $table->string('telegram')->nullable()->after('phone');
        });

        // 2. Settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 3. Instagram Usernames Marketplace
        Schema::create('instagram_usernames', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('type')->default('special'); // quad (رباعي), tri_semi (شبه ثلاثي), vintage (قديم), verified (موثق), special (مميز)
            $table->unsignedInteger('followers_count')->default(0);
            $table->string('creation_year')->nullable();
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // available, reserved, sold
            $table->text('delivery_info')->nullable(); // Account login details, email, pass, 2FA
            $table->timestamps();
        });

        // 4. Username Orders
        Schema::create('username_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instagram_username_id')->constrained('instagram_usernames')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->text('delivery_details')->nullable();
            $table->string('status')->default('completed'); // completed, refunded
            $table->timestamps();
        });

        // 5. SMM Providers (Standard SMM API v2)
        Schema::create('smm_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_url');
            $table->string('api_key');
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->string('currency')->default('USD');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 6. SMM Categories
        Schema::create('smm_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 7. SMM Services
        Schema::create('smm_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('smm_categories')->cascadeOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('price_per_1k', 10, 4);
            $table->unsignedInteger('min_quantity')->default(10);
            $table->unsignedInteger('max_quantity')->default(100000);
            $table->string('execution_type')->default('manual'); // api, manual
            $table->foreignId('provider_id')->nullable()->constrained('smm_providers')->nullOnDelete();
            $table->string('provider_service_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 8. SMM Orders
        Schema::create('smm_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('smm_services')->cascadeOnDelete();
            $table->string('link');
            $table->unsignedInteger('quantity');
            $table->decimal('charge', 10, 4);
            $table->integer('start_count')->nullable();
            $table->integer('remains')->nullable();
            $table->string('execution_type')->default('manual'); // api, manual
            $table->foreignId('provider_id')->nullable()->constrained('smm_providers')->nullOnDelete();
            $table->string('provider_order_id')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, partial, canceled
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 9. Crypto Deposits (USDT TRC20 / BEP20)
        Schema::create('crypto_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('crypto_currency')->default('USDT');
            $table->string('network')->default('TRC20'); // TRC20, BEP20, TON
            $table->string('wallet_address');
            $table->decimal('amount_usd', 10, 2);
            $table->string('txid')->nullable();
            $table->string('proof_image')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // 10. Financial Transactions Ledger
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // deposit, username_purchase, smm_order, refund, admin_adjustment
            $table->decimal('amount', 12, 2); // positive for credit, negative for debit
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('description');
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('crypto_deposits');
        Schema::dropIfExists('smm_orders');
        Schema::dropIfExists('smm_services');
        Schema::dropIfExists('smm_categories');
        Schema::dropIfExists('smm_providers');
        Schema::dropIfExists('username_orders');
        Schema::dropIfExists('instagram_usernames');
        Schema::dropIfExists('settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'balance', 'phone', 'telegram']);
        });
    }
};
