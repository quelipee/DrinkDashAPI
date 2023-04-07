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
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address_delivery');//entrega
            $table->string('address_billing');// cobrança
            $table->string('email')->unique();
            $table->string('phone_number');//numero de telefone/celular
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();//referencia com a tabela users
            $table->timestamps();
        });
    }

    /*id_cliente INT PRIMARY KEY,
    nome_cliente VARCHAR(255),
    endereco_entrega VARCHAR(255),
    endereco_cobranca VARCHAR(255),
    email_cliente VARCHAR(255),
    telefone_cliente VARCHAR(20)*/

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
