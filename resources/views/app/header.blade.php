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
                    <span>Witaj:<a href="{{ route('dashboard') }}"> {{ session('user_name') }}</a></span>
                    @php
                        $cart = session('cart', []);
                        $cartCount = collect($cart)->sum('quantity');
                    @endphp
                    <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-blue-600 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L21 13M7 13V6a1 1 0 011-1h6a1 1 0 011 1v7" />
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full px-1.5 py-0.5 font-bold shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
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