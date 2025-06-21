@extends('app.app')

@section('title', $product->name)

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-md p-8 mt-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>
            @php
                $images = array_filter(explode(',', $product->urlImages));
                $mainImage = $images[0] ?? null;
            @endphp

            @if ($mainImage)
                <img id="mainImage" src="{{ asset($mainImage) }}" alt="{{ $product->name }}" class="w-full h-[400px] object-cover rounded-xl mb-4 transition">
            @else
                <div class="w-full h-[400px] bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                    Brak zdjęcia
                </div>
            @endif

            @if(count($images) > 1)
                <div class="flex gap-2 overflow-x-auto">
                    @foreach ($images as $img)
                        <img onclick="document.getElementById('mainImage').src='{{ asset($img) }}'"
                             src="{{ asset($img) }}"
                             class="w-20 h-20 object-cover rounded border cursor-pointer hover:opacity-80 transition"
                             alt="Miniatura">
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h1 class="text-3xl font-bold text-blue-800 mb-4">{{ $product->name }}</h1>
            <p class="text-gray-700 text-lg mb-6">{{ $product->description }}</p>

            <ul class="mb-6 text-sm text-gray-600 space-y-1">
                <li><strong>Cena:</strong> <span class="text-green-600 font-semibold">{{ number_format($product->price, 2) }} zł</span></li>
                <li><strong>Stan magazynowy:</strong> {{ $product->stock_quantity }}</li>
                <li><strong>Waga:</strong> {{ $product->weight }} kg</li>
                <li><strong>Wymiary:</strong> {{ $product->dimensions }}</li>
                <li><strong>Kategoria:</strong> {{ $product->category->name ?? 'brak' }}</li>
            </ul>

            <a href="{{ route('products') }}"
               class="inline-block bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
               Powrót
            </a>
        </div>
    </div>
</div>
@endsection
