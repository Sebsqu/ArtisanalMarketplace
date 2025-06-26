@extends('app.app')

@section('title', 'Produkty')

@section('content')
<form method="GET" action="{{ route('products') }}" class="mb-8 p-6 bg-white rounded-xl shadow flex flex-wrap gap-4 items-end">
    <div class="flex flex-col">
        <label for="category" class="mb-1 text-sm text-gray-700 font-semibold">Kategoria</label>
        <select name="category" id="category" class="border rounded px-3 py-2 min-w-[180px]">
            <option value="">Wszystkie kategorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col">
        <label for="priceFrom" class="mb-1 text-sm text-gray-700 font-semibold">Cena od</label>
        <input type="text" id="priceFrom" placeholder="od" name="priceFrom" value="{{ request('priceFrom') }}" class="border rounded px-3 py-2 w-28">
    </div>
    <div class="flex flex-col">
        <label for="priceTo" class="mb-1 text-sm text-gray-700 font-semibold">Cena do</label>
        <input type="text" id="priceTo" placeholder="do" name="priceTo" value="{{ request('priceTo') }}" class="border rounded px-3 py-2 w-28">
    </div>
    <div class="flex flex-col flex-1 min-w-[200px]">
        <label for="search" class="mb-1 text-sm text-gray-700 font-semibold">Szukaj</label>
        <input type="text" id="search" placeholder="Szukaj produktu..." name="search" value="{{ request('search') }}" class="border rounded px-3 py-2">
    </div>
    <div class="flex flex-col">
        <label for="weight" class="mb-1 text-sm text-gray-700 font-semibold">Waga</label>
        <input type="text" id="weight" placeholder="Waga" name="weight" value="{{ request('weight') }}" class="border rounded px-3 py-2 w-28">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700 transition">Filtruj</button>
</form>

    <div class="mx-auto max-w-[1200px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-8">
        @foreach ($products as $product)
            @php
                $images = array_filter(explode(',', $product->urlImages));
                $firstImage = $images[0] ?? null;
            @endphp

            <div class="relative bg-white rounded-2xl shadow-md p-6 flex flex-col gap-3 border border-blue-100 hover:shadow-xl hover:-translate-y-1 transition-transform duration-200">

                <a href="{{ route('showProduct', $product->id) }}" class="block rounded-xl overflow-hidden mb-4">
                    @if ($firstImage)
                        <img src="{{ asset($firstImage) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                            Brak zdjęcia
                        </div>
                    @endif
                </a>

                <h2 class="text-xl font-bold text-blue-700 mb-1 truncate">{{ $product->name }}</h2>
                <p class="text-gray-600 text-sm mb-2 line-clamp-3">{{ $product->description }}</p>

                <span class="text-xs text-gray-500 mb-4 block">Dostępność: {{ $product->stock_quantity }}</span>

                <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-100 relative">

                    <span class="text-lg font-semibold text-green-600">{{ number_format($product->price, 2) }} zł</span>
                    <div class="flex items-center gap-2">
                        @auth
                            @php
                                $isFavorited = in_array($product->id, $favoritedProductIds);
                            @endphp

                            <form action="{{ route('addToFavorite', $product->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" aria-label="Polub ogłoszenie"
                                    class="{{ $isFavorited ? 'text-red-500 hover:text-gray-400' : 'text-gray-400 hover:text-red-500' }} transition-colors focus:outline-none cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        class="w-6 h-6">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                    </svg>
                                </button>
                            </form>
                            @auth
                                <form action="{{ route('addToCart', $product->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" aria-label="Dodaj do koszyka"
                                        class="text-gray-400 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L21 13M7 13V6a1 1 0 011-1h6a1 1 0 011 1v7" />
                                        </svg>
                                    </button>
                                </form>
                            @endauth
                        @endauth
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endsection