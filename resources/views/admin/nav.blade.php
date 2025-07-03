<nav class="flex flex-wrap gap-4 mb-6">
    <a href="{{ route('adminDashboard') }}"
        class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium hover:bg-blue-200 transition">
        Ogłoszenia
    </a>

    <a href="{{ route('adminUsers') }}"
        class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium hover:bg-blue-200 transition">
        Użytkownicy
    </a>

    <a href="{{ route('adminOrders') }}"
        class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium hover:bg-blue-200 transition">
        Zamówienia
    </a>
</nav>