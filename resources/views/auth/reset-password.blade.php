@extends('app.app')

@section('title', 'Ustaw nowe hasło')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 py-8">
    <form method="POST" action="{{ route('resetPassword') }}"
          class="max-w-md mx-auto bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block mb-1 font-semibold text-gray-700">Adres e-mail</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50"
                   placeholder="jan@email.com">
        </div>

        <div>
            <label for="password" class="block mb-1 font-semibold text-gray-700">Nowe hasło</label>
            <input type="password" name="password" id="password" required
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50"
                   placeholder="**********">
        </div>

        <div>
            <label for="password_confirmation" class="block mb-1 font-semibold text-gray-700">Powtórz hasło</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50"
                   placeholder="**********">
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 transition text-lg shadow-md mt-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            Zmień hasło
        </button>
    </form>
</div>
@endsection
