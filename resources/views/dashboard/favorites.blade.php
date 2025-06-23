@extends('app.app')

@section('title', 'Produkty')

@section('content')

    @include('dashboard.nav')

    <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
        Twoje polubione ogłoszenia
    </h2>

    <div class="mx-auto max-w-[1200px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-8">
        @if(count($favorites) > 0)
            @foreach ($favorites as $favorite)
                @php
                    $images = array_filter(explode(',', $favorite->urlImages));
                    $firstImage = $images[0] ?? null;
                @endphp

                <div class="relative bg-white rounded-2xl shadow-md p-6 flex flex-col gap-3 border border-blue-100 hover:shadow-xl hover:-translate-y-1 transition-transform duration-200">

                    <a href="{{ route('showProduct', $favorite->id) }}" class="block rounded-xl overflow-hidden mb-4">
                        @if ($firstImage)
                            <img src="{{ asset($firstImage) }}" alt="{{ $favorite->name }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                Brak zdjęcia
                            </div>
                        @endif
                    </a>

                    <h2 class="text-xl font-bold text-blue-700 mb-1 truncate">{{ $favorite->name }}</h2>
                    <p class="text-gray-600 text-sm mb-2 line-clamp-3">{{ $favorite->description }}</p>

                    <span class="text-xs text-gray-500 mb-4 block">Dostępność: {{ $favorite->stock_quantity }}</span>

                    <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-100 relative">

                        <span class="text-lg font-semibold text-green-600">{{ number_format($favorite->price, 2) }} zł</span>
                        <form action="{{ route('addToFavorite', $favorite->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" aria-label="Polub ogłoszenie" class="hover:text-gray-400 text-red-500 transition-colors focus:outline-none cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
                <p>Brak ulubionych</p>
        @endif
    </div>
@endsection