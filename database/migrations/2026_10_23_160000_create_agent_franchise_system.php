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
        // 1. Add Agent columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_agent')) {
                // smallint 0/1 for PostgreSQL safety
                $table->smallInteger('is_agent')->default(0);
            }
            if (!Schema::hasColumn('users', 'agent_city')) {
                $table->string('agent_city', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'agent_title')) {
                $table->string('agent_title', 150)->nullable();
            }
            if (!Schema::hasColumn('users', 'agent_commission_pct')) {
                $table->decimal('agent_commission_pct', 5, 2)->default(2.00);
            }
            if (!Schema::hasColumn('users', 'agent_total_earnings')) {
                $table->decimal('agent_total_earnings', 14, 2)->default(0.00);
            }
            if (!Schema::hasColumn('users', 'agent_appointed_at')) {
                $table->timestamp('agent_appointed_at')->nullable();
            }
        });

        // 2. Create agent_applications table
        if (!Schema::hasTable('agent_applications')) {
            Schema::create('agent_applications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('city', 100);
                $table->string('whatsapp_number', 50);
                $table->integer('estimated_team_size')->default(0);
                $table->text('experience_note')->nullable();
                $table->string('status', 30)->default('pending'); // pending, approved, rejected
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
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
        Schema::dropIfExists('agent_applications');

        Schema::table('users', function (Blueprint $table) {
            $cols = [
                'is_agent',
                'agent_city',
                'agent_title',
                'agent_commission_pct',
                'agent_total_earnings',
                'agent_appointed_at',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
