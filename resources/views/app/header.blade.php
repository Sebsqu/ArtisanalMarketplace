<nav class="bg-gray-100 p-4 mb-6 rounded-b-xl w-full">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between w-full">
        <div class="flex items-center gap-8">
            <a href="/" class="font-bold text-lg text-blue-700">Rynek rzemiosła</a>
            <a href="{{ route('products') }}" class="text-gray-700 hover:text-blue-600 transition">Produkty</a>
            @auth
                <a href="{{ route('addProductForm') }}" class="text-gray-700 hover:text-blue-600 transition">Dodaj produkt</a>
            @endauth
        </div>
        <div>
            @auth
                <div class="text-sm text-gray-600 flex flex-row items-center gap-6">
                    <span>Witaj:<a href="{{ route('dashboard') }}"> {{ auth()->user()->name }}</a></span>
                    <a href="{{ route('logout') }}" class="text-blue-600">Wyloguj</a>
                </div>
            @else
                <div class="flex flex-row items-center gap-6">
                    <a href="{{ route('loginForm') }}" class="hover:text-blue-600 transition">Login</a>
                    <a href="{{ route('registerForm') }}" class="hover:text-blue-600 transition">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>