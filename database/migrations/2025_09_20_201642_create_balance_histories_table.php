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
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('operation_type',[
                'deposit',
                'expense',
                'refund',
            ])->default('deposit');
            $table->float('amount_usd')->nullable();
            $table->float('amount_gel')->nullable();
            $table->float('usd_rate')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')
                ->on('backend_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('backend_user_id')->nullable();
            $table->foreign('backend_user_id')
                ->references('id')
                ->on('backend_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('balance_id')->nullable();
            $table->foreign('balance_id')
                ->references('id')
                ->on('balances')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_histories');
    }
};
