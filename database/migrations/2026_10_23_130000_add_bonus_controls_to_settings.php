<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'spin_min_reward')) {
                    $table->decimal('spin_min_reward', 8, 2)->default(1.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'spin_max_reward')) {
                    $table->decimal('spin_max_reward', 8, 2)->default(10.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'bottlecap_min_reward')) {
                    $table->decimal('bottlecap_min_reward', 8, 2)->default(1.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'bottlecap_max_reward')) {
                    $table->decimal('bottlecap_max_reward', 8, 2)->default(10.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day1_reward')) {
                    $table->decimal('streak_day1_reward', 8, 2)->default(1.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day2_reward')) {
                    $table->decimal('streak_day2_reward', 8, 2)->default(2.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day3_reward')) {
                    $table->decimal('streak_day3_reward', 8, 2)->default(3.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day4_reward')) {
                    $table->decimal('streak_day4_reward', 8, 2)->default(4.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day5_reward')) {
                    $table->decimal('streak_day5_reward', 8, 2)->default(5.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day6_reward')) {
                    $table->decimal('streak_day6_reward', 8, 2)->default(7.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'streak_day7_reward')) {
                    $table->decimal('streak_day7_reward', 8, 2)->default(10.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'red_envelope_max_claim')) {
                    $table->decimal('red_envelope_max_claim', 8, 2)->default(10.00)->nullable();
                }
                if (!Schema::hasColumn('settings', 'bonus_withdrawal_require_plan')) {
                    $table->boolean('bonus_withdrawal_require_plan')->default(true)->nullable();
                }
            });

            // Update default values and set correct official Telegram links
            try {
                DB::table('settings')->where('id', 1)->update([
                    'spin_min_reward' => 1.00,
                    'spin_max_reward' => 10.00,
                    'bottlecap_min_reward' => 1.00,
                    'bottlecap_max_reward' => 10.00,
                    'streak_day1_reward' => 1.00,
                    'streak_day2_reward' => 2.00,
                    'streak_day3_reward' => 3.00,
                    'streak_day4_reward' => 4.00,
                    'streak_day5_reward' => 5.00,
                    'streak_day6_reward' => 7.00,
                    'streak_day7_reward' => 10.00,
                    'red_envelope_max_claim' => 10.00,
                    'bonus_withdrawal_require_plan' => DB::raw('true'),
                    'telegram_channel' => 'https://t.me/+ClDoTZsIehNjMmI0',
                    'telegram_group' => 'https://t.me/+ssddpj32cYw1NDJk',
                ]);
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $cols = [
                    'spin_min_reward', 'spin_max_reward',
                    'bottlecap_min_reward', 'bottlecap_max_reward',
                    'streak_day1_reward', 'streak_day2_reward', 'streak_day3_reward',
                    'streak_day4_reward', 'streak_day5_reward', 'streak_day6_reward', 'streak_day7_reward',
                    'red_envelope_max_claim', 'bonus_withdrawal_require_plan'
                ];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
