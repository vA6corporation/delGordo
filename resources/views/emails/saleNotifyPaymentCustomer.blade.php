<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>DelGordo - Compra</title>
</head>
<body>
  <h1>Su compra a sido registrada correctamente</h1>
  <p>Puede obtener los detalles de su compra en el siguiente link</p>
  <p>
    <a href="{{ url()->to('/' . $sale['id'] . '/checkoutDetails') }}">Detalles de compra</a>
  </p>
  <a href="{{ url()->to('/' . $sale['id'] . '/checkout') }}">Proceso de pago</a>
</body>
</html>