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
        Schema::create('convertations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['buy', 'sell'])->nullable();
            $table->integer('amount');
            $table->decimal('converted_amount', 15, 2);
            $table->string('card_number', 16)->nullable();
            $table->integer('status')->default(0); // 0 - pending, 1 - completed, 2 - canceled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convertations');
    }
};
