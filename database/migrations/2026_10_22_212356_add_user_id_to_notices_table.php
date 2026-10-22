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
        if (Schema::hasTable('notices') && !Schema::hasColumn('notices', 'user_id')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
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
        if (Schema::hasTable('notices') && Schema::hasColumn('notices', 'user_id')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
