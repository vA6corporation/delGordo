<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Del Gordo - Carnes Premium</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
        <script src="https://kit.fontawesome.com/2e9575839f.js" crossorigin="anonymous"></script>

        <script src="./assets/js/functions.js"></script>
        <link href="./assets/css/styles.css" rel="stylesheet">
    </head>
    <body>
    <div id="loader" class="" onLoad="loader()" style="z-index: 23982193">
        <img src="./assets/images/loader2.gif" alt="" style="width:25%">
    </div>
    <div id="page" class="bye">
    <img src="./assets/images/logo.png" id="fixed_logo">    
        <@include('social')
        @include('header')
        @include('menu')

        @include('inicio')
        @include('store')
        @include('us')
        @include('consejos')
    </div>
    </body>
</html>
