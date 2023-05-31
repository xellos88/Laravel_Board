@extends('layout.layout')

@section('title', 'Useredit')

@section('contents')
    <h1>회원정보수정</h1>
    @include('layout.errorsvalidate')
    <form method="POST" action="{{ route('users.edit.post') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{$data-> name}}">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{$data-> email}}">
        <br>
        <label for="password">Before Password:</label>
        <input type="password" name="password" id="password">
        <br>
        <label for="password">After Password:</label>
        <input type="password" name="password" id="password">
        <br>
        <label for="password">After Password chk:</label>
        <input type="password" name="password" id="password">
        <br><br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{ route('boards.index') }}'">취소</button>
    </form>
@endsection
