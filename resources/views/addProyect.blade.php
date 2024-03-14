<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Simple Organization Management</title>
    </head>
    <body>
        <form action="/api/addproyect" method="POST">
            <label for="name">nombre:</label>
            <input type="text" name="name"><br>

            <label for="color">color:</label>
            <input type="color" name="color"><br>

            <label for="preview">imagen:</label>
            <input type="file" name="preview"><br><br>

            <input type="submit" value="Ingresar">
        </form> 
    </body>
</html>
