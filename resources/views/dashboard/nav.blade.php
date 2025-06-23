<nav class="flex flex-wrap gap-4 mb-6">
    <a href="{{ route('dashboard') }}"
        class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium hover:bg-blue-200 transition">
        Ogłoszenia użytkownika
    </a>
    <a href="{{ route('userSettingsForm', session('user_id')) }}"
        class="px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 transition">
        Ustawienia konta
    </a>
    <a href="{{ route('favorites') }}"
        class="px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 transition">
        Polubione ogłoszenia
    </a>
    <a href="#"
        class="px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 transition">
        Historia zamówień
    </a>
</nav>