@extends('app.app')

@section('title', "Profil użytkownika: " . $user->name)

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-md p-8 mt-10">
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Lewa kolumna: profil + oceny --}}
        <div class="md:w-1/2 flex flex-col gap-8">
            <div class="flex flex-col items-center">
                <img src="{{ $user->imageUrl ? asset('storage/' . $user->imageUrl) : asset('img/default-avatar.png') }}"
                     alt="Avatar"
                     class="w-32 h-32 rounded-full object-cover shadow border-4 border-blue-200 mb-2">
                <h1 class="text-3xl font-bold text-blue-800 mb-2 mt-2">{{ $user->name }}</h1>
                <div class="text-gray-600 mb-1">
                    <span class="font-semibold">E-mail:</span>
                    <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">{{ $user->email }}</a>
                </div>
                <div class="text-gray-600 mb-1">
                    <span class="font-semibold">Telefon:</span>
                    <a href="tel:{{ $user->phone_number }}" class="text-blue-600 hover:underline">{{ $user->phone_number }}</a>
                </div>
                <div class="text-gray-600 mb-1">
                    <span class="font-semibold">Miasto:</span> {{ $user->city }}
                </div>
                <div class="text-gray-600 mb-1">
                    <span class="font-semibold">Adres:</span> {{ $user->address }}
                </div>
                <div class="text-gray-600">
                    <span class="font-semibold">Kod pocztowy:</span> {{ $user->postal_code }}
                </div>
            </div>

            <div>
                @auth
                    @if($user->id !== session("user_id") && !$user->userRates->contains('user_id', session("user_id")))
                        <div class="bg-gradient-to-r from-blue-100 to-cyan-100 p-6 rounded-2xl shadow mb-6 border border-blue-200">
                            <form action="{{ route('rateUser', $user->id) }}" method="POST" class="flex flex-col gap-4">
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
                                <div class="flex flex-col gap-3 w-full">
                                    <label for="comment" class="font-semibold text-blue-800">Komentarz:</label>
                                    <input type="text" id="comment" name="comment" class="border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 w-full" placeholder="Wpisz swój komentarz...">
                                </div>
                                <div>
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition shadow w-full cursor-pointer">Wystaw</button>
                                </div>
                            </form>
                        </div>
                        @if (session('status'))
                            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center shadow">
                                {{ session('status') }}
                            </div>
                        @endif
                    @else
                        <div class="text-gray-500 mb-6 bg-gray-100 rounded-xl px-4 py-3 text-center shadow">
                            Oceniłeś już tego użytkownika lub nie możesz swojego profilu ocenić.
                        </div>
                    @endif
                @endauth

                <h1 class="text-xl font-bold text-blue-700 mb-4 mt-6">Oceny użytkownika</h1>
                <div>
                    @if ($rates->isEmpty())
                        <div class="text-gray-500 bg-gray-50 rounded-xl px-4 py-6 text-center shadow">Brak ocen dla tego użytkownika.</div>
                    @else
                        @foreach ($rates as $rate)
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
        </div>

        {{-- Prawa kolumna: ogłoszenia --}}
        <div class="md:w-1/2">
            <h2 class="text-2xl font-bold text-blue-700 mb-4 mt-2 md:mt-0">Produkty użytkownika</h2>
            @if($products->count())
                <div class="grid grid-cols-1 gap-6">
                    @foreach($products as $product)
                        <div class="bg-blue-50 rounded-xl shadow p-4 flex flex-col">
                            <a href="{{ route('showProduct', $product->id) }}">
                                @php
                                    $img = $product->urlImages ? explode(',', $product->urlImages)[0] : null;
                                @endphp
                                @if($img)
                                    <img src="{{ asset($img) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded mb-2">
                                @else
                                    <div class="w-full h-40 bg-gray-200 rounded flex items-center justify-center text-gray-400 mb-2">Brak zdjęcia</div>
                                @endif
                                <div class="font-bold text-blue-800 text-lg truncate">{{ $product->name }}</div>
                                <div class="text-gray-600 text-sm mb-1 line-clamp-2">{{ $product->description }}</div>
                                <div class="text-green-700 font-semibold mt-auto">{{ number_format($product->price, 2) }} zł</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500 text-center py-8">Użytkownik nie wystawił jeszcze żadnych produktów.</div>
            @endif
        </div>
    </div>
</div>
@endsection