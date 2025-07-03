@extends('app.app')

@section('title', 'Panel administratora: ' . auth()->user()->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('admin.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Panel Administratora - użytkownicy
        </h2>

        <form action="{{ route('adminUsers') }}" method="GET" class="mb-8 p-6 bg-white rounded-xl shadow flex flex-wrap gap-6 items-end">
            <div class="flex flex-col w-48">
                <label for="name" class="mb-1 text-sm text-gray-700 font-semibold">Imię i nazwisko</label>
                <input type="text" id="name" name="name" placeholder="Imię i nazwisko" value="{{ request('name') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition w-full">
            </div>
            <div class="flex flex-col w-48">
                <label for="email" class="mb-1 text-sm text-gray-700 font-semibold">Email</label>
                <input type="text" id="email" name="email" placeholder="Email" value="{{ request('email') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition w-full">
            </div>
            <div class="flex flex-col w-48">
                <label for="city" class="mb-1 text-sm text-gray-700 font-semibold">Miasto</label>
                <input type="text" id="city" name="city" placeholder="Miasto" value="{{ request('city') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition w-full">
            </div>
            <div class="flex flex-col w-48">
                <label for="phone_number" class="mb-1 text-sm text-gray-700 font-semibold">Numer telefonu</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="Numer telefonu" value="{{ request('phone_number') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition w-full">
            </div>
            <div class="flex flex-col w-32">
                <label for="is_active" class="mb-1 text-sm text-gray-700 font-semibold">Aktywny</label>
                <select name="is_active" id="is_active"
                        class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition w-full">
                    <option value="" {{ request('is_active') === null ? 'selected' : '' }}>---</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Tak</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nie</option>
                </select>
            </div>
            <div class="flex flex-col">
                <button type="submit"
                        class="bg-blue-600 text-white px-8 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                    Filtruj
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Imię i nazwisko</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Miasto</th>
                        <th class="px-4 py-2 border-b">Telefon</th>
                        <th class="px-4 py-2 border-b">Aktywny</th>
                        <th class="px-4 py-2 border-b">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $user->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                            <td class="px-4 py-2 border-b">{{ $user->email }}</td>
                            <td class="px-4 py-2 border-b">{{ $user->city }}</td>
                            <td class="px-4 py-2 border-b">{{ $user->phone_number }}</td>
                            <td class="px-4 py-2 border-b">
                                @if($user->is_active)
                                    <span class="text-green-600 font-bold">TAK</span>
                                @else
                                    <span class="text-red-600 font-bold">NIE</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('adminEditUser', $user->id) }}">Edytuj</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center">Brak użytkowników.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection