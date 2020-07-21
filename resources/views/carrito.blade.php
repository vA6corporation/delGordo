<?php
    $item = "
    <div class='row cart-row'>
        <div class='col-2' style='padding: 0.5em;'>
            <img src='https://i.pinimg.com/474x/e5/6a/72/e56a72d7e28f32817f4a6f939c8b2b91.jpg' alt=''>
        </div>
        <div class='col-6 row'>
            <div class='col-xs-12 col-lg-8'> Picanha Importada De Wakanda</div>
            <div class='col-xs-12 col-lg-4' style='font-weight:bold; color:red'> S/216.64</div>
        </div>
        <div class='col-4 row carrito-kg'>
            <div class='btn col-3'>-</div>
            <div class='col-4'>4kg</div>
            <div class='btn col-3'>+</div>
        </div>
    </div>
    "
?>
<section id="carrito">
    <div class="container">
        <div class="row" style="padding: 1em;">
            <h2>Tienes X ítems</h2>
        </div>
        <?php
        $x = 1;
            while($x <= 5) {
                echo $item;
                $x++;
            }
        ?>
        <div class="row">
            <div class="col-6 st-left"> Subtotal:</div>
            <div class="col-6 st-right"> S/1055.15</div>
        </div>
        <div class="row">
            <div class="col-6 st-left"> Delivery:</div>
            <div class="col-6 st-right"> S/70</div>
        </div>
        <div class="row">
            <div class="col-6 st-left"> Total:</div>
            <div class="col-6 st-right"> S/1125.15</div>
        </div>
        <div class="row" style="padding:1em">
            <div class="btn col-7 left" style="background-color:black; color:white"> <span><i class="fas fa-undo"></i></span> Seguir Comprando</i></div>
            <div class="col-2"></div>
            <div class="btn col-3 right" style="border: 1px solid black; color:black;"><span> <i class="fas fa-receipt"></i></span> Pagar</div>
        </div>
    </div>    
</section>