@extends('app.app')

@section('title', 'Panel: ' . auth()->user()->name)

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8">

        @include('admin.nav')

        <h2 class="text-3xl font-extrabold text-blue-800 mb-6 border-b-2 border-blue-200 pb-2">
            Panel Administratora
        </h2>

@endsection