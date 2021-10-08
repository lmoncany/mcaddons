

// jquery 8248


// A $( document ).ready() block.
jQuery( document ).ready(function() {


      if(jQuery('p.no_form').text() == "Formulaire indisponible"){
    console.log('no contact form');
    jQuery('.contact_form' ).hide();
      }


    jQuery(document).ready(function($) {
    jQuery(".owl-carousel").addClass(".owl-theme");
         jQuery(".owl-carousel").owlCarousel({
       loop: true,
       autoHeight:false,
       autoplay: true,
         margin: 10,
         nav: true,
         navigation: true,
         navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
       dots: false,
         items: 3,
         responsiveClass:true,
          responsive:{
              0:{
                  items:1
              },
              600:{
                  items:3
              },
              1000:{
                  items:3
              }
          }
       });
     });



});
