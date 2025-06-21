@extends('app.app')

@section('title', 'Resetowanie hasła')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 py-8">
    <form method="POST" action="{{ route('sendLink') }}"
          class="max-w-md mx-auto bg-white/90 rounded-3xl shadow-lg p-10 flex flex-col gap-6 backdrop-blur-md">
        @csrf

        <h1 class="text-2xl font-extrabold text-blue-700 text-center mb-2">Resetowanie hasła</h1>
        <p class="text-gray-500 text-center text-sm mb-4">Podaj swój adres e-mail, aby otrzymać link do resetu hasła.</p>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label for="email" class="block mb-1 font-semibold text-gray-700">Adres e-mail</label>
            <input type="email" name="email" id="email" required
                   class="w-full border border-transparent rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50"
                   placeholder="jan@email.com">
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-blue-700 text-white font-bold py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 transition text-lg shadow-md mt-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            Wyślij link do resetu hasła
        </button>
    </form>
</div>
@endsection
