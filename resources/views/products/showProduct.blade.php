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
               class="inline-block bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition mb-6">
               Powrót
            </a>

            <div class="flex items-center gap-4 bg-blue-50 rounded-xl p-4 shadow border border-blue-100 mt-6">
                <a href="{{ route('showUser', $product->user->id) }}">
                    <img src="{{ asset('storage/' . $product->user->imageUrl) }}"
                         alt="Avatar"
                         class="w-16 h-16 rounded-full object-cover shadow border-2 border-blue-300">
                </a>
                <div>
                    <div class="font-bold text-blue-800 text-lg">
                        <a href="{{ route('showUser', $product->user->id) }}" class="hover:underline">
                            {{ $product->user->name }}
                        </a>
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        <span class="font-semibold">E-mail:</span>
                        <a href="mailto:{{ $product->user->email }}" class="text-blue-600 hover:underline">
                            {{ $product->user->email }}
                        </a>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="font-semibold">Telefon:</span>
                        <a href="tel:{{ $product->user->phone_number }}" class="text-blue-600 hover:underline">
                            {{ $product->user->phone_number }}
                        </a>
                    </div>
                    <span class="text-xs text-gray-400 block mt-2">Sprzedawca</span>
            </div>
        </div>

        @auth
            @if($product->user_id !== session("user_id") && !$product->productRate->contains('user_id', session('user_id')))
                <div class="bg-gradient-to-r from-blue-100 to-cyan-100 p-6 rounded-2xl shadow mb-6 border border-blue-200 mt-6">
                    <form action="{{ route('rateProduct', $product->id) }}" method="POST" class="flex flex-col gap-4 w-full">
                        @csrf
                        <div class="flex flex-col gap-3 w-full">
                            <label for="rating" class="font-semibold text-blue-800">Wystaw ocenę:</label>
                            <select id="rating" name="rating" class="border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 w-full">
                                <option value="" disabled selected>Wybierz ocenę</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'gwiazdka' : 'gwiazdki' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex flex-col gap-3 w-full mt-2">
                            <label for="comment" class="font-semibold text-blue-800">Komentarz:</label>
                            <input type="text" id="comment" name="comment" class="border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 w-full" placeholder="Wpisz swój komentarz...">
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition shadow w-full cursor-pointer">Wystaw</button>
                        </div>
                    </form>
                </div>
            @if (session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center shadow mt-4">
                    {{ session('status') }}
                </div>
            @endif
            @else
                <div class="text-gray-500 mb-6 bg-gray-100 rounded-xl px-4 py-3 text-center shadow my-4">
                    Oceniłeś już ten produkt lub nie możesz swojego produktu ocenić.
                </div>
            @endif
        @endauth

        <h1 class="text-xl font-bold text-blue-700 mb-4 mt-6">Oceny użytkownika</h1>
        <div>
            @if ($productRates->isEmpty())
                <div class="text-gray-500 bg-gray-50 rounded-xl px-4 py-6 text-center shadow">Brak ocen dla tego użytkownika.</div>
            @else
                @foreach ($productRates as $rate)
                    <div class="bg-blue-50 p-4 rounded-xl shadow mb-4 border border-blue-100">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                            <span class="font-semibold text-yellow-500 flex items-center">
                                @for($i=0; $i<$rate->rate; $i++)
                                    <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                                <span class="ml-2">{{ $rate->rate }} / 5</span>
                            </span>
                            <span class="text-gray-500 text-sm">- {{ $rate->created_at->format('d.m.Y') }}</span>
                            <span class="text-gray-500 text-sm">- {{ $rate->user->name }}</span>
                        </div>
                        <div class="text-gray-700 italic">{{ $rate->comment }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
