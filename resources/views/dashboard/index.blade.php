@extends('app.app')

@section('title', 'Panel: ' . auth()->user()->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('dashboard.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Twoje ogłoszenia
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($products as $product)
                @php
                    $images = array_filter(explode(',', $product->urlImages));
                    $firstImage = $images[0] ?? null;
                @endphp

                <div class="relative bg-white rounded-2xl shadow-md p-6 flex flex-col gap-3 border border-blue-100 hover:shadow-xl hover:-translate-y-1 transition-transform duration-200">
                    <a href="{{ route('editProduct', $product->id) }}" class="block rounded-xl overflow-hidden mb-4">
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
