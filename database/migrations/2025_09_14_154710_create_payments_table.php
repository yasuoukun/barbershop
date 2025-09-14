<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->enum('method', ['cash','transfer'])->default('cash');
            $table->unsignedInteger('amount')->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->string('slip_path')->nullable(); // จะใช้ตอนอัปสลิป
            $table->timestamps();

            $table->index(['method', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
