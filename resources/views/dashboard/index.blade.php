@extends('app.app')

@section('title', 'Panel: ' . auth()->user()->name)

@section('content')
    <nav>
        <a href="">ogloszenia uzytkownika</a>
        <a href="">ustawienia konta</a>
        <a href="">historia zamowień</a>
        <a href="">lista zyczeń (ulubione)</a>
    </nav>
@endsection