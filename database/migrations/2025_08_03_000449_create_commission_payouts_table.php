<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_payouts', function (Blueprint $table) {
            $table->id();
            // A qué técnico se le pagó
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Cuánto se le pagó
            $table->decimal('amount', 8, 2);
            // Para qué período fue el pago
            $table->date('period_start');
            $table->date('period_end');
            // Quién y cuándo marcó el pago
            $table->foreignId('paid_by_user_id')->constrained('users');
            $table->timestamp('paid_at');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_payouts');
    }
};