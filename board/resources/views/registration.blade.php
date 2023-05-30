@extends('layout.layout')

@section('title',"Registration")

@section('contents')
    <h1>registraion</h1>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">Name : </label>
        <input type="name" name="name" id="name">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">Password : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="passwordchk">Password chk: </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br><br>
        <button type="submit">Registration</button>
        <button type="button" onclick="location.href = '{{route('users.login')}}'">cancel</button>
    </form>
@endsection