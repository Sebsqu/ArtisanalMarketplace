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

        <div>
            <label for="name" class="block text-gray-700 font-semibold mb-1">Nazwa produktu</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50">
        </div>

        <div>
            <label for="description" class="block text-gray-700 font-semibold mb-1">Opis produktu</label>
            <textarea id="description" name="description" required autocomplete="off" rows="4"
                      class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50 resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="price" class="block text-gray-700 font-semibold mb-1">Cena</label>
            <input type="text" id="price" name="price" required value="{{ old('price') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50">
        </div>

        <div>
            <label for="stock_quantity" class="block text-gray-700 font-semibold mb-1">Ilość sztuk</label>
            <input type="number" id="stock_quantity" min="0" max="999" step="1" name="stock_quantity" required value="{{ old('stock_quantity') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50">
        </div>

        <div>
            <label for="weight" class="block text-gray-700 font-semibold mb-1">Waga (kg)</label>
            <input type="text" id="weight" name="weight" required value="{{ old('weight') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50">
        </div>

        <div>
            <label for="dimensions" class="block text-gray-700 font-semibold mb-1">Wymiary (dł. x szer. x wys.)</label>
            <input type="text" id="dimensions" name="dimensions" value="{{ old('dimensions') }}" autocomplete="off"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" class="h-4 w-4">
            <label for="is_active" class="text-gray-700 text-sm">Aktywuj ogłoszenie od razu po dodaniu</label>
        </div>

        <div>
            <label for="category_id" class="block text-gray-700 font-semibold mb-1">Kategoria</label>
            <select id="category_id" name="category_id" required autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition text-gray-700">
                <option value="" disabled selected>Wybierz kategorię</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="images" class="block text-gray-700 font-semibold mb-1">Zdjęcia produktu (maks. 8)</label>
            <input
                type="file"
                name="images[]"
                id="images"
                accept="image/*"
                multiple
                class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition bg-blue-50"
                onchange="if(this.files.length > 8) { alert('Możesz wybrać maksymalnie 8 obrazków!'); this.value = ''; }"
            >
        </div>

        <input type="hidden" name="user_id" value="{{ session('user_id') }}">

        <div class="flex justify-between gap-4">
            <button type="reset"
                    class="w-full bg-gray-300 text-gray-700 font-bold py-2 rounded-xl hover:bg-gray-400 transition text-lg shadow-sm cursor-pointer">
                Zresetuj
            </button>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 hover:via-blue-700 transition text-lg shadow-md cursor-pointer">
                Dodaj produkt
            </button>
        </div>
    </form>
</div>
@endsection
