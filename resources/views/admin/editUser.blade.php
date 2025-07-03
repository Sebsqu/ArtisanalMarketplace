@extends('app.app')

@section('title', 'Edycja użytkownika: ' . $user->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('admin.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Panel Administratora - edycja użytkownika: {{ $user->name }}
        </h2>

        <form action="{{ route('adminSaveEditUser', $user->id) }}" method="POST"
              class="max-w-md mx-auto bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col items-center mb-6">
                @if($user->imageUrl)
                    <img src="{{ asset('storage/' . $user->imageUrl) }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover shadow mb-2 border-4 border-blue-200">
                @else
                    <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center mb-2 shadow border-4 border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
                <h1 class="text-2xl font-extrabold text-blue-700 text-center">Aktualizuj dane konta: {{ $user->name }}</h1>
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
                <label class="block mb-1 font-semibold text-gray-700" for="name">Imię i nazwisko</label>
                <input type="text" id="name" name="name" required value="{{ $user->name }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="Jan Kowalski">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="email">Adres e-mail</label>
                <input type="email" id="email" name="email" required value="{{ $user->email }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="jan@email.com">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="password">Hasło</label>
                <input type="password" id="password" name="password" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="**********">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="password_confirmation">Powtórz hasło</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="**********">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="phone_number">Numer telefonu</label>
                <input type="text" id="phone_number" name="phone_number" required value="{{ $user->phone_number }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="+49 123 456 789">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="city">Miasto</label>
                <input type="text" id="city" name="city" required value="{{ $user->city }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="Katowice">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="postal_code">Kod pocztowy</label>
                <input type="text" id="postal_code" name="postal_code" required value="{{ $user->postal_code }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="42-345">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="address">Adres</label>
                <input type="text" id="address" name="address" required value="{{ $user->address }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="Tadeusza Kościuszki 10/5">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="is_active">Aktywny</label>
                <input type="checkbox" id="is_active" name="is_active" {{ $user->is_active ? 'checked' : '' }} autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="Tadeusza Kościuszki 10/5">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="avatar">Zdjęcie profilowe</label>
                <input type="file" id="avatar" name="avatar"
                    class="w-full border border-transparent rounded-xl px-4 py-2 bg-blue-50">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="mt-2 w-24 h-24 rounded-full object-cover">
                @endif
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
@endsection