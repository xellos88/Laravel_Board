<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>write</title>
</head>
<body>
    @include('layout.errorsvalidate')
    <form action="{{route('boards.store')}}" method="post">
        @csrf
        <label for="title">제목 :</label>
        <input type="text" name="title" id="title" value="{{old('title')}}">
        <br>
        <label for="content">내용:</laber>
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>