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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->integer('year')->nullable();
            $table->string('vin')->nullable();
            $table->timestamp('arrival_date')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->boolean('is_sold')->default(0);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('car_model_id')->nullable();
            $table->foreign('car_model_id')
                ->references('id')
                ->on('car_models')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
