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
        Schema::table('crypto_deposits', function (Blueprint $table) {
            $table->string('payment_method')->default('crypto')->after('user_id'); // crypto, super_qi, zaincash, mastercard_manual
            $table->string('currency')->default('USD')->after('amount_usd'); // USD, IQD
            $table->decimal('amount_iqd', 14, 2)->nullable()->after('currency');
            $table->string('card_last_four', 8)->nullable()->after('proof_image');
            $table->string('sender_phone', 30)->nullable()->after('card_last_four');
            $table->string('wallet_address')->nullable()->change();
            $table->string('crypto_currency')->nullable()->change();
            $table->string('network')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crypto_deposits', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'currency', 'amount_iqd', 'card_last_four', 'sender_phone']);
        });
    }
};
