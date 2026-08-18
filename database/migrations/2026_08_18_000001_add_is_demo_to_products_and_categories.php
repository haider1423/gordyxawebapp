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
        if (Schema::hasTable('pcategories')) {
            Schema::table('pcategories', function (Blueprint $table) {
                if (!Schema::hasColumn('pcategories', 'is_demo')) {
                    $table->tinyInteger('is_demo')->default(0)->after('status');
                }
            });
        }

        if (Schema::hasTable('psub_categories')) {
            Schema::table('psub_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('psub_categories', 'is_demo')) {
                    $table->tinyInteger('is_demo')->default(0)->after('status');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'is_demo')) {
                    $table->tinyInteger('is_demo')->default(0)->after('status');
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
        if (Schema::hasTable('pcategories')) {
            Schema::table('pcategories', function (Blueprint $table) {
                if (Schema::hasColumn('pcategories', 'is_demo')) {
                    $table->dropColumn('is_demo');
                }
            });
        }

        if (Schema::hasTable('psub_categories')) {
            Schema::table('psub_categories', function (Blueprint $table) {
                if (Schema::hasColumn('psub_categories', 'is_demo')) {
                    $table->dropColumn('is_demo');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'is_demo')) {
                    $table->dropColumn('is_demo');
                }
            });
        }
    }
};
