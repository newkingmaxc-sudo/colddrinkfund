<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Payment Methods Custom QR Code Image
        if (Schema::hasTable('payment_methods')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_methods', 'qr_code')) {
                    $table->string('qr_code', 500)->nullable();
                }
            });
        }

        // 2. Settings: Flash Recharge Event, Vault Rate & Admin Secret Key
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'flash_event_active')) {
                    $table->smallInteger('flash_event_active')->default(0)->nullable();
                }
                if (!Schema::hasColumn('settings', 'flash_event_bonus_pct')) {
                    $table->decimal('flash_event_bonus_pct', 5, 2)->default(15.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'flash_event_ends_at')) {
                    $table->timestamp('flash_event_ends_at')->nullable();
                }
                if (!Schema::hasColumn('settings', 'flash_event_title')) {
                    $table->string('flash_event_title', 255)->default('⚡ 2-Hour Flash Recharge Boost (+15% Extra Instant Cashback)')->nullable();
                }
                if (!Schema::hasColumn('settings', 'vault_interest_rate')) {
                    $table->decimal('vault_interest_rate', 5, 2)->default(1.20)->nullable();
                }
                if (!Schema::hasColumn('settings', 'admin_secret_key')) {
                    $table->string('admin_secret_key', 64)->default('portal99')->nullable();
                }
            });
        }

        // 3. Vault Accounts (Piggy Bank / بچت تجوری)
        if (!Schema::hasTable('vault_accounts')) {
            Schema::create('vault_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique()->index();
                $table->decimal('balance', 14, 2)->default(0.00);
                $table->decimal('total_interest_earned', 14, 2)->default(0.00);
                $table->timestamp('last_interest_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Lottery Tickets (اتوار لکی ڈرا ٹکٹس)
        if (!Schema::hasTable('lottery_tickets')) {
            Schema::create('lottery_tickets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('ticket_number', 32)->index();
                $table->string('source', 64)->default('package_purchase');
                $table->string('status', 32)->default('active'); // active, won, expired
                $table->string('prize_name', 128)->nullable();
                $table->decimal('prize_amount', 10, 2)->default(0.00)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_tickets');
        Schema::dropIfExists('vault_accounts');

        if (Schema::hasTable('payment_methods')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                if (Schema::hasColumn('payment_methods', 'qr_code')) {
                    $table->dropColumn('qr_code');
                }
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $cols = ['flash_event_active', 'flash_event_bonus_pct', 'flash_event_ends_at', 'flash_event_title', 'vault_interest_rate', 'admin_secret_key'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
