

// jquery 8248


// A $( document ).ready() block.
jQuery( document ).ready(function() {

  function updateSize(){
        var minHeight=parseInt(jQuery('.owl-item').eq(0).css('height'));
        jQuery('.owl-item').each(function () {
            var thisHeight = parseInt(jQuery(this).css('height'));
            minHeight=(minHeight<=thisHeight?minHeight:thisHeight);
        });
        jQuery('.owl-wrapper-outer').css('height',minHeight+'px');

        /*show the bottom part of the cropped images*/
        jQuery('.owl-carousel .owl-item img').each(function(){
            var thisHeight = parseInt(jQuery(this).css('height'));
            if(thisHeight>minHeight){
                jQuery(this).css('margin-top',-1*(thisHeight-minHeight)+'px');
            }
        });

    }

});
