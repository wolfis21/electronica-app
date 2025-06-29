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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 45);
            $table->string('phone', 45)->nullable();
            $table->string('dni', 45)->unique()->nullable();
            $table->string('address', 45)->nullable();
            $table->foreignId('companies_id')->constrained('companies')->onDelete('restrict'); // NOT NULL, FK a 'companies'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
