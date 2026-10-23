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
        if (!Schema::hasTable('app_versions')) {
            Schema::create('app_versions', function (Blueprint $table) {
                $table->id();
                $table->integer('version_code')->default(100);
                $table->string('version_name', 50)->default('1.0.0');
                $table->string('apk_url', 500)->default('/download-apk');
                $table->string('apk_size', 50)->nullable()->default('8.5 MB');
                $table->boolean('is_forced')->default(false);
                $table->string('title')->default('🚀 New Update Available! / نیا ورژن دستیاب ہے');
                $table->text('changelog')->nullable();
                $table->integer('min_supported_version')->default(100);
                $table->boolean('is_active')->default(true);
                $table->integer('download_count')->default(0);
                $table->timestamps();
            });

            // Insert initial default version
            DB::table('app_versions')->insert([
                'version_code' => 101,
                'version_name' => '1.1.0',
                'apk_url' => '/download-apk',
                'apk_size' => '9.2 MB',
                'is_forced' => DB::raw('false'),
                'title' => 'Cold Drink Fund v1.1.0 Update',
                'changelog' => "🥤 Cold Drink Bottling Plant (Tap to Collect)\n🍾 Lucky Bottle Cap Daily Rewards\n🎡 Lucky Spin Wheel with Instant Cash\n⚡ Super-Fast Deposit & Instant Withdrawal Processing\n🛡️ Official Cold Drink Fund Android Application",
                'min_supported_version' => 100,
                'is_active' => DB::raw('true'),
                'download_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'app_version_code')) {
                    $table->integer('app_version_code')->default(101)->nullable();
                }
                if (!Schema::hasColumn('settings', 'app_version_name')) {
                    $table->string('app_version_name', 50)->default('1.1.0')->nullable();
                }
                if (!Schema::hasColumn('settings', 'app_apk_url')) {
                    $table->string('app_apk_url', 500)->default('/download-apk')->nullable();
                }
                if (!Schema::hasColumn('settings', 'app_force_update')) {
                    $table->boolean('app_force_update')->default(false)->nullable();
                }
                if (!Schema::hasColumn('settings', 'app_update_title')) {
                    $table->string('app_update_title', 255)->default('🚀 New Update Available! / نیا ورژن دستیاب ہے')->nullable();
                }
                if (!Schema::hasColumn('settings', 'app_update_changelog')) {
                    $table->text('app_update_changelog')->nullable();
                }
            });

            // Seed default settings values if not set
            try {
                DB::table('settings')->where('id', 1)->update([
                    'app_version_code' => 101,
                    'app_version_name' => '1.1.0',
                    'app_apk_url' => '/download-apk',
                    'app_force_update' => DB::raw('false'),
                    'app_update_title' => '🚀 New Update Available! / نیا ورژن دستیاب ہے',
                    'app_update_changelog' => "🥤 Cold Drink Bottling Plant (Tap to Collect)\n🍾 Lucky Bottle Cap Daily Rewards\n🎡 Lucky Spin Wheel with Instant Cash\n⚡ Super-Fast Deposit & Instant Withdrawal Processing\n🛡️ Official Cold Drink Fund Android Application",
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
        Schema::dropIfExists('app_versions');
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $cols = ['app_version_code', 'app_version_name', 'app_apk_url', 'app_force_update', 'app_update_title', 'app_update_changelog'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
