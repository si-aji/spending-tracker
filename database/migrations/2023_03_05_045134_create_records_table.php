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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('type', ['income', 'expense', 'transfer'])->default('expense');
            $table->unsignedBigInteger('from_wallet_id');
            $table->unsignedBigInteger('to_wallet_id')->nullable();
            $table->double('amount')->default(0);
            $table->enum('extra_type', ['amount', 'percentage'])->default('amount');
            $table->double('extra_percentage')->default(0);
            $table->double('extra_amount')->default(0);
            $table->date('date');
            $table->time('time');
            $table->dateTime('datetime');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('from_wallet_id')
                ->references('id')
                ->on('wallets')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('to_wallet_id')
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
        Schema::dropIfExists('records');
    }
};
