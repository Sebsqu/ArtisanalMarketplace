@extends('app.app')

@section('title', 'Historia zamówień')

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('dashboard.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Historia zamówień
        </h2>

        @if ($orders->isEmpty())
            <p class="text-gray-600">Brak historii zamówień.</p>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Zamówienie #{{ $order->id }}</h3>
                        <p class="text-gray-600 mb-4">Data zamówienia: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        <ul class="space-y-2">
                            @foreach ($order->orderItems as $item)
                                <li class="flex justify-between">
                                    <span>
                                        @if(isset($item->product_id))
                                            <a href="{{ route('showProduct', $item->product_id) }}" class="text-blue-700 hover:underline" target="_blank">
                                                {{ $item->product_name ?? 'Produkt' }}
                                            </a>
                                        @else
                                            {{ $item->product_name ?? 'Produkt usunięty' }}
                                        @endif
                                        ({{ $item->quantity }})
                                    </span>
                                    <span>{{ number_format($item->price, 2) }} zł</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 border-t pt-4">
                            <strong>Łączna kwota: {{ number_format($order->total_price, 2) }} zł</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
@endsection