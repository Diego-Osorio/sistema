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
        // Crear la tabla de ventas
        Schema::create('venta', function (Blueprint $table) {
            $table->id();
            $table->enum("metodo_pago", ["efectivo", "tarjeta", "transferencia", "otro", "dividido"])->default("efectivo");
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente'); // Corregido
            $table->decimal('total', 10, 2)->default(0);
            $table->date("fecha_venta")->default(now());
            $table->timestamps();
        });

        // Crear la tabla intermedia venta_producto
        Schema::create('venta_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('venta')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_producto');
        Schema::dropIfExists('venta');
    }
};