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
            <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col gap-3 border border-blue-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-200">
                <h2 class="text-xl font-bold text-blue-700 mb-1 truncate">{{ $product->name }}</h2>
                <p class="text-gray-600 text-sm mb-2 line-clamp-3">{{ $product->description }}</p>
                <div class="flex flex-row items-center justify-between mt-auto pt-2 border-t border-gray-100">
                    <span class="text-lg font-semibold text-green-600">{{ number_format($product->price, 2) }} zł</span>
                    <span class="text-xs text-gray-500">Dostępność: {{ $product->stock_quantity }}</span>
                </div>
            </div>
        @endforeach
    </div>
@endsection