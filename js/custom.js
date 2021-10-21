

// jquery 8248


// A $( document ).ready() block.
jQuery( document ).ready(function() {


      if(jQuery('p.no_form').text() == "Formulaire indisponible"){
    console.log('no contact form');
    jQuery('.contact_form' ).hide();
      }

      if(jQuery('.google-map-wrap > div').is(':empty')){
    jQuery('.bloc__location' ).hide();
      }

      if(jQuery('.owl-stage').is(':empty')){
    jQuery('.bloc__gallery' ).hide();
      }

      if(jQuery('.chapters-list').is(':empty')){
    jQuery('.chapters-list' ).hide();
      }






});
