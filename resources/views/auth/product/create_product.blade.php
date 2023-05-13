@extends('layouts.default')
@section('title','adicionar produto')
@section('content')
    <div class=" mt-20 flex items-center justify-center">
        <form method="post" action="{{ route('add_product') }}" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="p-2 border rounded-md shadow-2xl">
                <div>
                    <img src="https://placehold.co/600x400/png"
                         style="height: 400px; width: 600px;" alt="">
                </div>
                <div class="space-y-2 p-2">
                    <div class="pb-2 pt-2">
                        <input type="file" name="img_product">
                    </div>
                    <div class="grid grid-cols-2">
                        <label class="text-gray-600 font-semibold" for="username">Nome</label>
                        <input type="text" name="name" id="username"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" placeholder="Nome do produto">
                    </div>
                    <div class="grid grid-cols-2">
                        <label class="text-gray-600 font-semibold" for="description">Descrição</label>
                        <input type="text" name="description" id="description"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"
                               placeholder="Descrição do produto">
                    </div>
                    <div class="grid grid-cols-2">
                        <label class="text-gray-600 font-semibold" for="price">Preço</label>
                        <input type="number" name="price" id="price"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"
                               placeholder="Preço">
                    </div>
                    <div class="grid grid-cols-2">
                        <label class="text-gray-600 font-semibold" for="category">Categoria</label>
                        <input type="text" name="category" id="category"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"
                               placeholder="Descrição do produto">
                    </div>

                    <div class="grid grid-cols-2">
                        <label class="text-gray-600 font-semibold" for="available_quantity">Quantidade</label>
                        <input type="number" name="available_quantity" id="available_quantity"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"
                               placeholder="Quantidade">
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-400 text-white font-semibold border hover:bg-blue-500 rounded-md px-2 py-1">adicionar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
{{--
name' => $product->name,
'description' => $product->description,
'img_product' => $product->image_url,
'price' => random_int(5,99),
'category' => 'cerveja'--}}
