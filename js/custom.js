

// jquery 8248


// A $( document ).ready() block.
jQuery( document ).ready(function() {


      if(jQuery('p.no_form').text() == "Formulaire indisponible"){
    console.log('no contact form');
    jQuery('.contact_form' ).hide();
      }

      if(jQuery('p.testimonial').text() == ""){
    jQuery('.bloc__reviews' ).hide();
      }

      if(jQuery('.google-map-wrap > div').is(':empty')){
    jQuery('.bloc__location' ).hide();
      }





});
