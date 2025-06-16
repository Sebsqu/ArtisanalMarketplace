@extends('app.app')

@section('title', 'Produkty')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        @foreach($categories as $category)
            <a href="{{ route('products', ['category' => $category->id]) }}"
            class="block h-40 flex items-center justify-center rounded-2xl bg-blue-100 text-blue-700 text-2xl font-bold shadow hover:bg-blue-200 transition text-center">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
@endsection