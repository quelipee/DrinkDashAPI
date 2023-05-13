<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>@yield('title')</title>
</head>
<body>
<header class="bg-gray-900 text-gray-100 py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
        <a href="/" class="text-2xl font-bold">
            One Bebidas
        </a>
        <div>
            <a href="{{ route('add_product') }}" class="mr-6 hover:text-gray-400">
                Adicionar Produto
            </a>
            <a href="{{ route('users') }}" class="mr-6 hover:text-gray-400">
                Usuários
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:text-gray-400">
                    Sair
                </button>
            </form>
        </div>
    </div>
</header>
@yield('content')
</body>
</html>
