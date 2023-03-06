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
        if (!Schema::hasColumn('records', 'timezone')) {
            Schema::table('records', function (Blueprint $table) {
                $table->string('timezone')->nullable()->after('note');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('records', 'timezone')) {
            Schema::table('records', function (Blueprint $table) {
                $table->dropColumn('timezone');
            });
        }
    }
};
