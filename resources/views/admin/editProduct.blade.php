@extends('app.app')

@section('title', 'Edycja produktu: ' . $product->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">
        @include('admin.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Panel Administratora - edycja ogłoszenia: {{ $product->name }}
        </h2>


    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 py-8">
        <form action="{{ route('adminSaveEditProduct', $product->id) }}" method="POST"
            class="max-w-lg w-full bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md"
            enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col items-center mb-2">
                <div class="bg-blue-100 rounded-full p-3 mb-2 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-blue-700 text-center">Edytuj produkt: {{ $product->name }}</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-2 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="name" class="block text-gray-700 font-semibold mb-1">Nazwa</label>
                <input type="text" id="name" name="name" placeholder="Nazwa" required value="{{ $product->name }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">
            </div>

            <div>
                <label for="description" class="block text-gray-700 font-semibold mb-1">Opis</label>
                <textarea id="description" name="description" placeholder="Opis" required autocomplete="off"
                        class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50 resize-none"
                        rows="4">{{ $product->description }}</textarea>
            </div>

            <div>
                <label for="price" class="block text-gray-700 font-semibold mb-1">Cena</label>
                <input type="text" id="price" name="price" placeholder="Cena" required value="{{ $product->price }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">
            </div>

            <div>
                <label for="stock_quantity" class="block text-gray-700 font-semibold mb-1">Ilość</label>
                <input type="number" id="stock_quantity" min="0" max="999" step="1" name="stock_quantity" placeholder="Ilość" required value="{{ $product->stock_quantity }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">
            </div>

            <div>
                <label for="weight" class="block text-gray-700 font-semibold mb-1">Waga (kg)</label>
                <input type="text" id="weight" name="weight" placeholder="Waga (kg)" required value="{{ $product->weight }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">
            </div>

            <div>
                <label for="dimensions" class="block text-gray-700 font-semibold mb-1">Wymiary</label>
                <input type="text" id="dimensions" name="dimensions" placeholder="długość x szerokość x wysokość" value="{{ $product->dimensions }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active === 1 ? 'checked' : null }} class="h-4 w-4">
                <label for="is_active" class="text-gray-700 text-sm">Czy ogłoszenie ma byc aktywne?</label>
            </div>

            <div>
                <label for="category_id" class="block text-gray-700 font-semibold mb-1">Kategoria</label>
                <select id="category_id" name="category_id" required autocomplete="off"
                        class="w-full border border-transparent rounded-xl px-4 py-2 bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition text-gray-700">
                    <option value="" disabled selected>Wybierz kategorię</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id === $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $images = array_filter(explode(',', $product->urlImages));
            @endphp

            @if(count($images) > 0)
                <label class="font-semibold text-gray-700">Aktualne zdjęcia:</label>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($images as $img)
                        <img src="{{ asset($img) }}" alt="Zdjęcie produktu"
                            class="w-40 h-40 object-cover rounded-lg border shadow-sm mx-auto">
                    @endforeach
                </div>
            @endif

            <div>
                <label for="images" class="block text-gray-700 font-semibold mb-1">Dodaj nowe zdjęcia (maks. 8):</label>
                <input type="file"
                    name="images[]"
                    id="images"
                    accept="image/*"
                    multiple
                    class="w-full rounded-xl px-4 py-2 bg-blue-50 border border-transparent focus:ring-2 focus:ring-blue-400 transition"
                    onchange="if(this.files.length > 8) { alert('Możesz dodać maksymalnie 8 zdjęć!'); this.value = ''; }">
            </div>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-between gap-4">
                <a href="{{ route('adminDashboard') }}"
                class="block text-center w-full bg-gray-300 text-gray-700 font-bold py-2 rounded-xl hover:bg-gray-400 transition text-lg shadow-sm">
                    Powrót
                </a>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 hover:via-blue-700 transition text-lg shadow-md cursor-pointer">
                    Zapisz
                </button>
            </div>
        </form>
    </div>
        </div>
@endsection
