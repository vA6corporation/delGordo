<section id="us" style="width:100%">
    <!--<p id="us_text">
        Somos la empresa peruana líder en el procesamiento y venta de carnes 
        Premium. Nuestra cadena de valor inicia con la búsqueda de los mejores
        proveedores en cada una de las categorías que representamos para así
        lograr la excelencia y calidad que ofrecemos a nuestros clientes.
    </p>-->
</section>
<script>
    $(document).ready(function(){
    var w = window.innerWidth;
    if(w <= 1024){
      $("#us").html("<img src='./assets/images/mobile/us_bgm.png' style='width: 100%;'>")
    }
    else{
        $("#us").html("<img src='./assets/images/us_bg.png' style='width: 100%;'>")
  
    }
});
</script>