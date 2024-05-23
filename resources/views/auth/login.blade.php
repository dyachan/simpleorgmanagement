<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Simple Organization Management</title>
    </head>
    <body>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <form action="/login" method="POST">
            @csrf
            <label for="email">email:</label>
            <input type="text" name="email"><br>

            <label for="password">password:</label>
            <input type="password" name="password"><br>

            <input type="submit" value="Ingresar">
        </form> 
    </body>
</html>
