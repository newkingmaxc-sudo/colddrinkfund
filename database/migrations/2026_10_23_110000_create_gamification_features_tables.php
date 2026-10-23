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
        if (!Schema::hasTable('missions_claimed')) {
            Schema::create('missions_claimed', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('mission_key');
                $table->decimal('reward_amount', 12, 2)->default(0);
                $table->timestamps();

                $table->index(['user_id', 'mission_key']);
            });
        }

        if (!Schema::hasTable('red_envelopes')) {
            Schema::create('red_envelopes', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->decimal('total_amount', 12, 2);
                $table->decimal('remaining_amount', 12, 2);
                $table->integer('max_claimers')->default(50);
                $table->integer('claimed_count')->default(0);
                $table->decimal('min_reward', 12, 2)->default(5);
                $table->decimal('max_reward', 12, 2)->default(100);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('red_envelope_claims')) {
            Schema::create('red_envelope_claims', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('red_envelope_id');
                $table->unsignedBigInteger('user_id');
                $table->decimal('amount', 12, 2);
                $table->timestamps();

                $table->unique(['red_envelope_id', 'user_id']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_bottling_collect_at')) {
                $table->timestamp('last_bottling_collect_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_bottlecap_opened_at')) {
                $table->timestamp('last_bottlecap_opened_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_leader_salary_claimed_at')) {
                $table->timestamp('last_leader_salary_claimed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_free_spin_at')) {
                $table->timestamp('last_free_spin_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('red_envelope_claims');
        Schema::dropIfExists('red_envelopes');
        Schema::dropIfExists('missions_claimed');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_bottling_collect_at',
                'last_bottlecap_opened_at',
                'last_leader_salary_claimed_at',
                'last_free_spin_at'
            ]);
        });
    }
};
