<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Se asume que el id de users es un BigInt sin signo.
            // El after() es opcional, solo para ordenar la columna en la BD.
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Definir la restricción de clave foránea
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // Opcional: si se borra un usuario, el campo user_id en reviews se vuelve null.
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Eliminar primero la restricción de clave foránea
            $table->dropForeign(['user_id']);
            // Luego eliminar la columna
            $table->dropColumn('user_id');
        });
    }
};