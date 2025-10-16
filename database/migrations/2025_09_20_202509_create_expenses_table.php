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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('amount_gel')->nullable();
            $table->string('amount_usd')->nullable();
            $table->float('usd_rate')->nullable();
            $table->unsignedBigInteger('backend_user_id')->nullable();
            $table->foreign('backend_user_id')
                ->references('id')
                ->on('backend_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('balance_id')->nullable();
            $table->foreign('balance_id')
                ->references('id')
                ->on('balances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('operation_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
