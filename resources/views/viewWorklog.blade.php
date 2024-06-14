<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Simple Organization Management</title>
    </head>
    <body>
        @include('utils.jsutils')
        @include('utils.log')
        
        @include('components.viewWorklog')

        <som-viewworklog style="width:100%;" som-view="month" som-users="1, 2, 3, 4">
            <div style="width: 100px; height: 100px; background-color: black"></div>
        </som-viewworklog>

   </body>
</html>
