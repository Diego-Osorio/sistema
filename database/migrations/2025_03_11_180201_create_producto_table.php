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
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();  // Si deseas permitir valores nulos en 'nombre'
            $table->string('codigo')->unique();
            $table->decimal('precio', 10, 2);
            $table->unsignedBigInteger('categoria_id'); // Relación con categoría
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5); // Umbral de alerta
            $table->timestamps();

            
            $table->foreign('categoria_id')->references('id')->on('categoria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};

