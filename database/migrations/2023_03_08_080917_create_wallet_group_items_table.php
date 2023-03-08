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
        Schema::create('wallet_group_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('wallet_group_id');
            $table->unsignedBigInteger('wallet_id');
            $table->timestamps();

            $table->foreign('wallet_group_id')
                ->references('id')
                ->on('wallet_groups')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_group_items');
    }
};
