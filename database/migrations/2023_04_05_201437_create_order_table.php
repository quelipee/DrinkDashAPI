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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_order');//data do pedido
            $table->string('status_order');//status do pedido
            $table->foreignId('client_id')->constrained('client')->cascadeOnUpdate()->cascadeOnDelete();//cliente que solicitou
            $table->timestamps();
        });
    }

    /*
    id_pedido INT PRIMARY KEY,
    data_pedido DATETIME,
    status_pedido VARCHAR(50),
    id_cliente INT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
    */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
