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
        // 1. Users table security columns
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'registration_ip')) {
                    $table->string('registration_ip', 64)->nullable()->index();
                }
                if (!Schema::hasColumn('users', 'last_login_ip')) {
                    $table->string('last_login_ip', 64)->nullable()->index();
                }
                if (!Schema::hasColumn('users', 'is_frozen')) {
                    $table->boolean('is_frozen')->default(false)->nullable();
                }
            });
        }

        // 2. Settings table emergency controls
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'deposit_status')) {
                    $table->string('deposit_status', 32)->default('active')->nullable();
                }
                if (!Schema::hasColumn('settings', 'maintenance_mode')) {
                    $table->boolean('maintenance_mode')->default(false)->nullable();
                }
                if (!Schema::hasColumn('settings', 'maintenance_message')) {
                    $table->text('maintenance_message')->nullable();
                }
            });
        }

        // 3. Payment methods table rotation & limit columns
        if (Schema::hasTable('payment_methods')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_methods', 'account_title')) {
                    $table->string('account_title')->nullable();
                }
                if (!Schema::hasColumn('payment_methods', 'account_type')) {
                    $table->string('account_type', 64)->nullable();
                }
                if (!Schema::hasColumn('payment_methods', 'daily_limit')) {
                    $table->decimal('daily_limit', 12, 2)->default(50000.00)->nullable();
                }
                if (!Schema::hasColumn('payment_methods', 'is_active')) {
                    $table->boolean('is_active')->default(true)->nullable();
                }
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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $cols = ['registration_ip', 'last_login_ip', 'is_frozen'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('users', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $cols = ['deposit_status', 'maintenance_mode', 'maintenance_message'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('payment_methods')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $cols = ['account_title', 'account_type', 'daily_limit', 'is_active'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('payment_methods', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
