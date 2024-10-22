@extends('layouts.app')
@section('content')
    <h1>Formulário de Exemplo</h1>

    <form action="{{ route('welcome') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="message">Mensagem:</label>
            <textarea id="message" name="message" rows="3" required></textarea>
        </div>

        <button type="submit" >Enviar</button>
    </form>
@endsection
