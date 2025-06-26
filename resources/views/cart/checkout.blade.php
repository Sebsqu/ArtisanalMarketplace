@extends('app.app')

@section('title', 'Koszyk')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6 mt-8">
        <h1 class="text-2xl font-bold mb-6 text-blue-700">Finalizowanie zamówienia</h1>
        @php 
            $totalPrice = 0; 
            $totalQuantity = 0;
        @endphp

        @forelse ($items as $item)
            <div class="flex flex-col sm:flex-row items-center gap-6 border-b py-6 last:border-b-0">
                <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
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
                <div class="flex-1 flex flex-col gap-1">
                    <div class="font-semibold text-lg text-blue-800">{{ $item['name'] }}</div>
                    <div class="text-gray-500 text-sm">Cena/szt: <span class="font-semibold">{{ number_format($item['price'], 2) }} zł</span></div>
                    <div class="text-gray-500 text-sm">Ilość: <span class="font-semibold">{{ $item['quantity'] }}</span></div>
                    <div class="text-gray-500 text-sm">Suma: <span class="font-semibold">{{ number_format($item['price'] * $item['quantity'], 2) }} zł</span></div>
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
            <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4">
                <div class="text-gray-700 text-lg">Łącznie: <span class="font-bold text-blue-700">{{ $totalQuantity }}</span> szt</div>
                <div class="text-xl font-bold text-green-700">Suma: {{ number_format($totalPrice, 2) }} zł</div>
            </div>
        @endif

        <form id="mainOrderForm" class="mt-8 bg-blue-50 rounded-xl p-6 shadow flex flex-col gap-4" onsubmit="event.preventDefault(); showBlikModal();">
            <div class="mb-2">
                <label for="name" class="block text-blue-800 font-semibold mb-1">Imię i nazwisko</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('name', $user->name) }}">
            </div>
            <div class="mb-2">
                <label for="email" class="block text-blue-800 font-semibold mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('email', $user->email) }}">
            </div>
            <div class="mb-2">
                <label for="city" class="block text-blue-800 font-semibold mb-1">Miasto</label>
                <input type="text" id="city" name="city" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('city', $user->city) }}">
            </div>
            <div class="mb-2">
                <label for="address" class="block text-blue-800 font-semibold mb-1">Adres dostawy</label>
                <input type="text" id="address" name="address" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('address', $user->address) }}">
            </div>
            <div class="mb-2">
                <label for="postal_code" class="block text-blue-800 font-semibold mb-1">Kod pocztowy</label>
                <input type="text" id="postal_code" name="postal_code" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('postal_code', $user->postal_code) }}">
            </div>
            <div class="mb-2">
                <label for="phone_number" class="block text-blue-800 font-semibold mb-1">Telefon</label>
                <input type="text" id="phone_number" name="phone_number" class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400" required value="{{ old('phone_number', $user->phone_number) }}">
            </div>
            <div class="mb-2">
                <input type="hidden" id="total_price" name="total_price" value="{{ $totalPrice }}" readonly>
                <p class="text-lg font-bold text-blue-700">Kwota do zapłaty: {{ number_format($totalPrice, 2) }} zł</p>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition shadow mt-2 w-full cursor-pointer">
                Opłać BLIKIEM
            </button>
        </form>
    </div>

    <div id="blikModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-xs flex flex-col items-center">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Płatność BLIK</h2>
            <form id="blikForm" method="POST" action="{{ route('placeOrder') }}" class="w-full flex flex-col gap-4">
                @csrf
                <input type="hidden" id="modal_name" name="name" value="">
                <input type="hidden" id="modal_email" name="email" value="">
                <input type="hidden" id="modal_city" name="city" value="">
                <input type="hidden" id="modal_address" name="address" value="">
                <input type="hidden" id="modal_postal_code" name="postal_code" value="">
                <input type="hidden" id="modal_phone_number" name="phone_number" value="">
                <input type="hidden" id="modal_total_price" name="total_price" value="{{ $totalPrice }}">
                <label for="blik_code" class="block text-blue-800 font-semibold mb-1 text-center">Wpisz 6-cyfrowy kod BLIK</label>
                <input type="text" id="blik_code" name="blik_code" maxlength="6" minlength="6" pattern="\d{6}" required
                    class="w-full p-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-400 text-center text-lg tracking-widest"
                    placeholder="______" autocomplete="off">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition shadow mt-2 w-full cursor-pointer">
                    Zapłać
                </button>
                <button type="button" onclick="hideBlikModal()" class="text-gray-500 hover:text-red-500 mt-2 w-full cursor-pointer">Anuluj</button>
            </form>
        </div>
    </div>

    <script>
        function showBlikModal() {
            document.getElementById('modal_name').value = document.getElementById('name').value;
            document.getElementById('modal_email').value = document.getElementById('email').value;
            document.getElementById('modal_city').value = document.getElementById('city').value;
            document.getElementById('modal_address').value = document.getElementById('address').value;
            document.getElementById('modal_postal_code').value = document.getElementById('postal_code').value;
            document.getElementById('modal_phone_number').value = document.getElementById('phone_number').value;
            document.getElementById('blikModal').classList.remove('hidden');
        }
        function hideBlikModal() {
            document.getElementById('blikModal').classList.add('hidden');
        }
    </script>
@endsection