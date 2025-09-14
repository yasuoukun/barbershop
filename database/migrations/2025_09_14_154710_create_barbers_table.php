<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barbers', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->decimal('commission_rate', 5, 2)->default(50.00); // ส่วนแบ่ง %
            $table->timestamps();

            $table->index('display_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbers');
    }
};
