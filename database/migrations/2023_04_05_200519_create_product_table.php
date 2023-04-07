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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('img_product');
            $table->float('price');
            $table->string('category');
            $table->timestamps();
        });
    }

    /*id_produto INT PRIMARY KEY,
    nome_produto VARCHAR(255),
    descricao_produto VARCHAR(255),
    imagem_produto VARCHAR(255),
    preco_produto DECIMAL(10, 2),
    categoria_produto VARCHAR(50)*/

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
