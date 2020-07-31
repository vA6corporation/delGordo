window.onload = function loader(){
    document.getElementById("loader").classList.add('bye');
    document.getElementById("page").classList.remove('bye');    
}


function menu_link(x){
    close_menu();
    document.getElementById(x).classList.add('rotate-90-ccw');
}

function menu_link_animate(x){
    document.getElementById(x).classList.add('rotate-90-ccw');
}

function menu_link_desanimate(x){
    document.getElementById(x).classList.remove('rotate-90-ccw');
}



function open_menu(){
    document.getElementById("menu").classList.remove('hide');
    document.getElementById("menu").classList.remove('slide-out-right');
    document.getElementById("menu").classList.add('slide-in-right');
}

function close_menu(){
    document.getElementById("menu").classList.remove('slide-in-right');
    document.getElementById("menu").classList.add('slide-out-right');
    document.getElementById("menu").classList.add('hide');
}


$(document).ready(function(){
    var w = window.innerWidth;
    if(w <= 1024){
      $(".carrou1").html("<img src='./assets/images/mobile/ca_1.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")
      $(".carrou2").html("<img src='./assets/images/mobile/ca_2.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")
      $(".carrou3").html("<img src='./assets/images/mobile/ca_3.jpg' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'>")  

    }
    else{
        $(".carrou1").html("<img src='./assets/images/mobile/ca1.webp' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat1.png' class='cat'></div>");
        $(".carrou2").html("<img src='./assets/images/mobile/ca2.webp' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat2.png' class='cat'></div>");
        $(".carrou3").html("<img src='./assets/images/mobile/ca3.webp' class='d-block w-100 home_img_carrousel' alt='100% Carne Premium'><div class='carousel-caption d-none d-md-block home_CC'> <img src='./assets/images/cat2.png' class='cat'></div>"); 
    }
});

$("a[href='#top']").click(function() {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });

