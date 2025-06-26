@extends('app.app')

@section('title', 'Koszyk')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6 mt-8">
        <h1 class="text-2xl font-bold mb-6 text-blue-700">Twój koszyk</h1>
        @php 
            $totalPrice = 0; 
            $totalQuantity = 0;
        @endphp
        @forelse ($items as $item)
            <div class="flex flex-col sm:flex-row items-center gap-6 border-b py-6 last:border-b-0">
                {{-- Obrazek produktu --}}
                <div class="w-28 h-28 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
                    @php
                        $img = null;
                        if (isset($item['urlImages'])) {
                            $images = array_filter(explode(',', $item['urlImages']));
                            $img = $images[0] ?? null;
                        }
                    @endphp
                    @if($img)
                        <img src="{{ asset($img) }}" alt="{{ $item['name'] }}" class="object-cover w-full h-full">
                    @else
                        <span class="text-gray-400">Brak zdjęcia</span>
                    @endif
                </div>
                {{-- Dane produktu --}}
                <div class="flex-1 flex flex-col gap-1">
                    <div class="font-semibold text-lg text-blue-800">{{ $item['name'] }}</div>
                    <div class="text-gray-500 text-sm">Cena/szt: <span class="font-semibold">{{ number_format($item['price'], 2) }} zł</span></div>
                    <div class="text-gray-500 text-sm">Ilość: <span class="font-semibold">{{ $item['quantity'] }}</span></div>
                    <div class="text-gray-500 text-sm">Suma: <span class="font-semibold">{{ number_format($item['price'] * $item['quantity'], 2) }} zł</span></div>
                </div>
                {{-- Akcje --}}
                <div class="flex flex-col gap-2 items-end">
                    <form action="{{ route('removeItem', $item['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer font-semibold px-3 py-1 rounded transition">Usuń</button>
                    </form>
                </div>
            </div>
            @php 
                $totalPrice += $item['price'] * $item['quantity']; 
                $totalQuantity += $item['quantity'];
            @endphp
        @empty
            <div class="text-gray-500 text-center py-8">Koszyk jest pusty.</div>
        @endforelse
        
        @if(!empty($items))
            <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4 pt-6">
                <div class="text-gray-700 text-lg">Łącznie: <span class="font-bold text-blue-700">{{ $totalQuantity }}</span> szt</div>
                <div class="text-xl font-bold text-green-700">Suma: {{ number_format($totalPrice, 2) }} zł</div>
                <a href="{{ route('checkout') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition shadow">Kup</a>
            </div>
        @endif
    </div>
@endsection