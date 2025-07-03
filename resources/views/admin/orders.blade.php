@extends('app.app')

@section('title', 'Panel administratora: ' . auth()->user()->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('admin.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Panel Administratora - zamówienia
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Klient</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Miasto</th>
                        <th class="px-4 py-2 border-b">Adres</th>
                        <th class="px-4 py-2 border-b">Telefon</th>
                        <th class="px-4 py-2 border-b">Kwota</th>
                        <th class="px-4 py-2 border-b">Data</th>
                        <th class="px-4 py-2 border-b">Produkty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $order->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->name }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->email }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->city }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->address }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->phone_number }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($order->total_price, 2) }} zł</td>
                            <td class="px-4 py-2 border-b">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-2 border-b">
                                <ul class="list-disc pl-4">
                                    @foreach($order->OrderItems as $item)
                                        <li>
                                            {{ $item->product_name }} (x{{ $item->quantity }}) - {{ number_format($item->price, 2) }} zł
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-2 text-center">Brak zamówień.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection