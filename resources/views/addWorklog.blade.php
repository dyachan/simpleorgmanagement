<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Simple Organization Management</title>
    </head>
    <body>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="/addworklog" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">
    
            <label for="start">inicio:</label>
            <input type="datetime-local" name="start" value="{{ old('start') }}"><br>
            <label for="end">fin:</label>
            <input type="datetime-local" name="end" value="{{ old('end') }}"><br>

            <label for="proyect">proyecto:</label>
            <select name="proyect_id">
                <option value="0">-</option>
                @foreach ($proyects as $proyect)
                    <option value="{{$proyect->id}}" {{ old('proyect_id') == $proyect->id ? 'selected' : '' }}>{{$proyect->name}}</option>
                @endforeach
            </select><br>

            <label for="description">descripción:</label>
            <textarea name="description" rows="4" cols="30">{{ old('description') }}</textarea>

            <input type="submit" value="Ingresar">
        </form> 
    </body>
</html>
