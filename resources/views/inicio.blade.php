<section id="inicio" class="centrar">
    <div id="carouselHome" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators home_CI">
            <li data-target="#carouselHome" data-slide-to="0" class="active home_CII"></li>
            <li data-target="#carouselHome" data-slide-to="1" class="home_CII"></li>
            <li data-target="#carouselHome" data-slide-to="2" class="home_CII"></li>
        </ol>
        <div class="carousel-inner" id="ca_mobile">
            <div class="carousel-item active carrou1">
            </div>
            <div class="carousel-item carrou2">
            </div>
            <div class="carousel-item carrou3">
            </div>
        </div>

        <a class="carousel-control-prev" href="#carouselHome" role="button" data-slide="prev" style="width: 15vw;">
            <span aria-hidden="true"><i class="fas fa-long-arrow-alt-left home_arrows_carrousel"></i></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselHome" role="button" data-slide="next" style="width: 15vw;">
            <span aria-hidden="true"><i class="fas fa-long-arrow-alt-right home_arrows_carrousel"></i></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <a href="#store" onclick="scroll()" class="btn" id="shop_now">COMPRA AQUÍ</a>
</section>
<script type="text/javascript">
$(document).ready(function(){
    var w = window.innerWidth;
    if(w <= 1024){
      $(".carrou1").html("<img src='./assets/images/mobile/ca_1.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")
      $(".carrou2").html("<img src='./assets/images/mobile/ca_2.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")
      $(".carrou3").html("<img src='./assets/images/mobile/ca_3.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")  

    }
    else{
      $(".carrou1").html("<img src='./assets/images/ca1.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat1.png' class='cat'></div>");
      $(".carrou2").html("<img src='./assets/images/ca2.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat2.png' class='cat'></div>");
      $(".carrou3").html("<img src='./assets/images/ca3.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat2.png' class='cat'></div>"); 
    }
});
</script>
