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
  <a href="{{ url()->to('/' . $sale['id'] . '/checkoutDetails') }}">Detalles de compra</a>
  <h2>PREMIER BEEF SAC</h2>
    
    <p>Cuenta <strong>BCP</strong> soles: 194-99363721-0-93 </p>
    <p>Cuenta <strong>BBVA</strong> soles: 0011-0872-0100022992-90</p>
    <p>Cuenta <strong>Interbank</strong> soles: 368-3001975904</p>
    <p><strong>Yape</strong>: 999617899</p>
    <p><strong>Lukita</strong>: 920252114</p>
</body>
</html>