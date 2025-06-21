@extends('app.app')

@section('title', 'Dodaj nowy produkt')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 py-8">
        <form action="{{ route('addProduct') }}" method="POST"
              class="max-w-lg w-full bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col items-center mb-2">
                <div class="bg-blue-100 rounded-full p-3 mb-2 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-blue-700 text-center">Dodaj nowy produkt</h1>
                <p class="text-gray-500 text-center text-sm mt-1">Uzupełnij wszystkie dane, aby dodać produkt do sklepu.</p>
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

            <input type="text" name="name" placeholder="Nazwa" required value="{{ old('name') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <input type="text" name="description" placeholder="Opis" required value="{{ old('description') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <input type="text" name="price" placeholder="Cena" required value="{{ old('price') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <input type="number" min="0" max="999" step="1" name="stock_quantity" placeholder="Ilość" required value="{{ old('stock_quantity') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <input type="text" name="weight" placeholder="Waga (kg)" required value="{{ old('weight') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <input type="text" name="dimensions" placeholder="Wymiary: długość x szerokość x wysokość" value="{{ old('dimensions') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50">

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="h-4 w-4">
                <label for="is_active" class="text-gray-700 text-sm">Aktywuj ogłoszenie od razu po dodaniu</label>
            </div>

            <select name="category_id" required autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition text-gray-700">
                <option value="" disabled selected>Wybierz kategorię</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label for="images" class="font-semibold text-gray-700">Dodaj zdjęcia produktu (maksymalnie 8):</label>
            <input
                type="file"
                name="images[]"
                id="images"
                accept="image/*"
                multiple
                class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                onchange="if(this.files.length > 8) { alert('Możesz wybrać maksymalnie 8 obrazków!'); this.value = ''; }"
            >

            <input type="hidden" name="user_id" value={{ session('user_id') }}>

            <div class="flex justify-between gap-4">
                <button type="reset"
                        class="w-full bg-gray-300 text-gray-700 font-bold py-2 rounded-xl hover:bg-gray-400 transition text-lg shadow-sm">
                    Zresetuj
                </button>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 hover:via-blue-700 transition text-lg shadow-md">
                    Dodaj produkt
                </button>
            </div>
        </form>
    </div>
@endsection
