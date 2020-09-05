<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nueva venta registrada</title>
</head>
<body>
  <h1>Tenemos una nueva venta registrada</h1>
  <a href="{{ url()->to('/sales' . '/' . $sale['id'] . '/details') }}">Detalles de venta</a>
</body>
</html>