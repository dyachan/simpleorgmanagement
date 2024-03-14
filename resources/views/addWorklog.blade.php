<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Simple Organization Management</title>
    </head>
    <body>
        <form action="/api/addworklog" method="POST">
            <input type="hidden" name="user_id" value="1">
    
            <label for="start">inicio:</label>
            <input type="datetime-local" name="start"><br>
            <label for="end">fin:</label>
            <input type="datetime-local" name="end"><br>

            <label for="proyect">proyecto:</label>
            <select name="proyect">
                <option value="value1">Value 1</option>
                <option value="value2" selected>Value 2</option>
                <option value="value3">Value 3</option>
            </select><br>

            <label for="description">descripción:</label>
            <input type="text" name="description"><br><br>

            <input type="submit" value="Ingresar">
        </form> 
    </body>
</html>
