@extends('app.app')

@section('title', 'Rejestracja')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 py-8">
        <form action="{{ route('createUser') }}" method="POST"
              class="max-w-md mx-auto bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md">
            @csrf

            <div class="flex flex-col items-center mb-2">
                <div class="bg-blue-100 rounded-full p-3 mb-2 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-blue-700 text-center">Załóż konto</h1>
                <p class="text-gray-500 text-center text-sm mt-1">Dołącz do naszego sklepu i korzystaj z promocji!</p>
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
                <input type="text" id="name" name="name" required value="{{ old('name') }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="Jan Kowalski">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="email">Adres e-mail</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}" autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="jan@email.com">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="password">Hasło</label>
                <input type="password" id="password" name="password" required autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="**********">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700" for="password_confirmation">Powtórz hasło</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="off"
                    class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-gray-400 bg-blue-50"
                    placeholder="**********">
            </div>
            
            @if (session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <button type="submit"
                class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 hover:via-blue-700 transition text-lg shadow-md mt-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 cursor-pointer">
                Zarejestruj się
            </button>

            <div class="mt-2 text-center text-sm text-gray-500">
                Masz już konto?
                <a href="{{ route('loginForm') }}" class="text-blue-600 hover:underline font-semibold">Zaloguj się</a>
            </div>
        </form>
    </div>
@endsection