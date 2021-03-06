<?php
/**
 * Plugin Name: MC addons
 * Plugin URI: https://flowragency.com
 * Description: Display content using a shortcode to insert in a page or post
 * Version: 2.4.3
 * Text Domain: mc-addons
 * Author: Loic Moncany
 * Author URI: https://flowragency.com
 */

 ###############
 # Defaults and options
 ###############

 /**
  * Never worry about cache again!
  */
 function my_load_scripts($hook) {

     // create my own version codes
     $my_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/custom.js' ));
     $my_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'css/global.css' ));

     //
     wp_enqueue_script( 'custom_js', plugins_url( 'js/custom.js', __FILE__ ), array(), $my_js_ver );
     wp_register_style( 'my_css',    plugins_url( 'css/global.css',    __FILE__ ), false,   $my_css_ver );
     wp_enqueue_style ( 'my_css' );

 }
 add_action('wp_enqueue_scripts', 'my_load_scripts');

 ###############
 # Automatic updates
 ###############



 ###############
 # Logic
 ###############




 add_shortcode( 'galler2y', 'modified_gallery_shortcode' );

 function modified_gallery_shortcode($attr)
 {
     // Replace WP gallery with OWL Carousel using gallery shortcode -- just add `owl=true`
     //
     // [gallery owl="true" link="none" size="medium" ids="378,377,376,375,374,373"]

     if( isset($attr['owl']))
     {
         $attr['itemtag']="span";

         $output = gallery_shortcode($attr);

         $output = strip_tags($output,'<a><img><span><figcaption>'); // remove extra tags, but keep these
         $output = str_replace("span", "div", $output); // replace span for div -- removes gallery wrap
         $output = str_replace('gallery-item', "item", $output); // remove class attribute

         $output = "<div class=\"owl-carousel\" >$output</div>"; // wrap in div

         // begin styles and js

         static $js_loaded; // only create once
         if( empty ( $js_loaded )) {

         ob_start();
         ?>
         <style>
             .owl-carousel .item{
                 margin: 3px;
             }
             .owl-carousel .item img{
                 display: block;
                 width: 100%;
                 height: auto;
             }
         </style>
         <script defer src="https://owlgraphic.com/owlcarousel/owl-carousel/owl.carousel.js"></script>
         <script>
             jQuery('head').append('<link defer id="owl-carousel-css" rel="stylesheet" href="https://owlgraphic.com/owlcarousel/owl-carousel/owl.carousel.css" type="text/css" />');
             jQuery('head').append('<link defer id="owl-theme-css" rel="stylesheet" href="https://owlgraphic.com/owlcarousel/owl-carousel/owl.theme.css" type="text/css" />');

             jQuery(document).ready(function () {

                 // pulling example from -- including CSS
                 // http://owlgraphic.com/owlcarousel/demos/images.html

                 // notice I replaced #id with .class for when you want to have more than one on a page
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
         </script>
         <?php
         $js_loaded = ob_get_clean(); // store in static var

         // add the HTML output
         $output .= $js_loaded;
         }
     }
     else
     {
         // default gallery
         $output = gallery_shortcode($attr);
     }

     return $output; // final html
   }

 // get image Gallery
  function get_remote_gallery($atts) {



    $slug = get_post_field( 'post_name', get_post() );
    $id = get_post_field( 'id', get_post() );


     $request =  wp_safe_remote_get('https://malta-communities.com/wp-json/wp/v2/business/?slug=' . $slug);

     $body = wp_remote_retrieve_body( $request );
     $data = json_decode( $body );
     $gallery_images = $data[0]->image_upload_2;
     $business_id = $data[0]->id;
     $links_attachments =  'https://malta-communities.com/wp-json/wp/v2/media?parent=' . $business_id;

     $request =  wp_safe_remote_get('https://malta-communities.com/wp-json/wp/v2/media?parent=' . $business_id);
     $body = wp_remote_retrieve_body( $request );
     $dataGallery = json_decode( $body );

     $gallery_attached = $dataGallery;
     // echo var_dump($gallery_images);
     // echo '<hr>';


     // image source
     //  https://malta-communities.com/wp-json/wp/v2/media/[ID]

     if ( is_wp_error( $request ) ) {
        return false;
     } else {
           // $gallery = $data["image_upload_1"];
            // return var_dump($data["_embedded"]["wp:featuredmedia"][0]["source_url"] );


             // return var_dump($gallery_images);
             echo '<div class="owl-carousel">';
             foreach ($gallery_attached as $key => $jsons) {
               foreach($jsons as $key => $value) {
                 $images_type = $jsons->media_details->sizes->large->mime_type;
                 $images_url = $jsons->media_details->sizes->large->source_url;
                 $images_content = $jsons->media_details->sizes->large;
                 //var_dump($images_content);

                   if($key == 'id' && $images_type != 'image/gif' && $images_content != null){

                echo '<div class="item">
                 <img class="img-responsive" src="' . $images_url . '" />
                 </div>';
                 }

                 }

             }

             echo '</div>';

     }





 }
 add_shortcode('remote-gallery', 'get_remote_gallery');





// get image url of a post
function get_image_url($url) {
  $request =  wp_safe_remote_get($url);
  //
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );
  $images_url = $data->media_details->sizes->large->source_url;

  return $images_url;

}

 /// display gmap
 function google_map_location($atts) {

 $business_location =  get_post_meta( get_the_ID(), 'business_address', true);

 //return var_dump($business_location);

 $height = isset($atts['height']) ?  $atts['height'] : 300;
 $height .= 'px';
 $rand = '_' . rand(1000001, 9999999);

 $lat = isset($business_location['lat']) ? $business_location['lat'] : 38.0658495;
 $lng = isset($business_location['lng']) ? $business_location['lng'] : 46.3238727;
 $zoom = isset($atts['zoom']) ? $atts['zoom'] : 17;

 $title = isset($atts['title']) ? $atts['title'] : '';
 $icon = isset($atts['icon']) ? $atts['icon'] : '';

 $disable_default_UI = isset($atts['disabledefaultui']) ? ($atts['disabledefaultui'] === 'true' ? 'true' : 'false') : 'false';

 return "
   <div class='google-map-wrap'>
     <div id='address-map$rand' style='height: $height' class='google-map'></div>
   </div>
   <script async defer
     src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAY50ddOWaLBeuRz8tRrgZ_fjAK9cA3OT0&v=3.24&callback=renderMap$rand'></script>
     <script>
       function renderMap$rand(){
         var mapCanvas = document.getElementById('address-map$rand');
         if(!mapCanvas) return;
         var latLang = new google.maps.LatLng($lat, $lng);
         var mapOptions = {
           disableDefaultUI: false,
           center: latLang,
           overviewMapControl: false,
           zoom: $zoom,
           mapTypeId: google.maps.MapTypeId.ROADMAP,
           scrollwheel: true,
         }
         var map = new google.maps.Map(mapCanvas, mapOptions);
         var marker = new google.maps.Marker({
           position: latLang,
           map: map,
           title: '$title',
           icon: '$icon'
         });
       };
     </script>";

 }
 add_shortcode('gmap-location', 'google_map_location');





// display opening hours on single business
function display_opening_hours($atts) {

$repeat_field =  get_post_meta( get_the_ID(), 'opening_hours', true);

 if ( $repeat_field ) {
 $values = explode( '|', $repeat_field );
   foreach ($values as $value) {
   echo  "<p style='text-align:center;'>$value</p>";
   }
 }

}
add_shortcode('opening-hours', 'display_opening_hours');


// display reviews on single business

function display_client_reviews($atts) {

$repeat_field = get_post_meta( get_the_ID(), 'client_reviews_copy');
ob_start();
  if ( $repeat_field  != null) {
  echo '<div>';
  foreach ($repeat_field as $field) {
  $values = explode( '| ', $field );
  echo "<div class='review'><span class='testimonial'>{$values[0]}</span><span class='name' style='margin-top: 10px;'>??????????????? - {$values[1]}</span></div>";
  }
  echo '</div>';
  }

return ob_get_clean();
}


add_shortcode('client-reviews', 'display_client_reviews');



// custom-query-hp
add_action( 'pp_query_category', function( $query ) {
    // Here we set the query to fetch posts with
    // post type of 'custom-post-type1' and 'custom-post-type2'
    $taxonomy = get_queried_object();
    $cat_id = $taxonomy->cat_ID;

    $categories[] = $cat_id;

        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $categories,
                    'operator' => 'IN',
                ),
            )
        );
        $query->set( 'tax_query' , $args );

} );



// Display Network Menu


function display_network_navigation($atts) {
return '<div id="network_navigation" class="elementor-element elementor-element-38968df4 elementor-align-center elementor-widget__width-auto elementor-fixed elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="38968df4" data-element_type="widget" data-settings="{&quot;_position&quot;:&quot;fixed&quot;,&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
					<ul class="elementor-icon-list-items">
							<li class="elementor-icon-list-item">
					<a href="https://francaisamalte.com/" target="_blank">						<span class="elementor-icon-list-icon">
							<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 512 512"><circle cx="256" cy="256" fill="#f0f0f0" r="256"></circle><path d="m512 256c0-110.071-69.472-203.906-166.957-240.077v480.155c97.485-36.172 166.957-130.007 166.957-240.078z" fill="#d80027"></path><path d="m0 256c0 110.071 69.473 203.906 166.957 240.077v-480.154c-97.484 36.171-166.957 130.006-166.957 240.077z" fill="#0052b4"></path><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>						</span>
										<span class="elementor-icon-list-text"></span>
											</a>
									</li>
								<li class="elementor-icon-list-item">
					<a href="https://espanolesenmalta.com/" target="_blank">						<span class="elementor-icon-list-icon">
							<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 512 512"><path d="m0 256c0 31.314 5.633 61.31 15.923 89.043l240.077 22.261 240.077-22.261c10.29-27.733 15.923-57.729 15.923-89.043s-5.633-61.31-15.923-89.043l-240.077-22.261-240.077 22.261c-10.29 27.733-15.923 57.729-15.923 89.043z" fill="#ffda44"></path><g fill="#d80027"><path d="m496.077 166.957c-36.171-97.484-130.006-166.957-240.077-166.957s-203.906 69.473-240.077 166.957z"></path><path d="m15.923 345.043c36.171 97.484 130.006 166.957 240.077 166.957s203.906-69.473 240.077-166.957z"></path></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>						</span>
										<span class="elementor-icon-list-text"></span>
											</a>
									</li>
								<li class="elementor-icon-list-item">
					<a href="https://englishinmalta.net/" target="_blank">						<span class="elementor-icon-list-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewBox="0 0 473.68 473.68" style="enable-background:new 0 0 473.68 473.68;" xml:space="preserve">
<g>
	<path style="fill:#29337A;" d="M41.712,102.641c-15.273,22.168-26.88,47.059-33.918,73.812h107.734L41.712,102.641z"></path>
	<path style="fill:#29337A;" d="M170.511,9.48c-27.288,7.947-52.56,20.628-74.814,37.168l74.814,74.814V9.48z"></path>
	<path style="fill:#29337A;" d="M101.261,430.982c20.874,14.607,44.195,25.915,69.25,33.211v-102.45L101.261,430.982z"></path>
	<path style="fill:#29337A;" d="M10.512,306.771c7.831,25.366,19.831,48.899,35.167,69.833l69.833-69.833H10.512z"></path>
</g>
<g>
	<path style="fill:#FFFFFF;" d="M45.619,97.144c-1.324,1.81-2.629,3.646-3.908,5.501l73.816,73.812H7.793   c-1.746,6.645-3.171,13.418-4.345,20.284h141.776L45.619,97.144z"></path>
	<path style="fill:#FFFFFF;" d="M95.767,427.074c1.802,1.343,3.654,2.621,5.493,3.908l69.25-69.242v102.45   c6.653,1.945,13.41,3.624,20.284,4.974V332.05L95.767,427.074z"></path>
	<path style="fill:#FFFFFF;" d="M5.25,286.487c1.47,6.873,3.205,13.642,5.258,20.284h105.001l-69.833,69.833   c7.595,10.377,16.017,20.115,25.168,29.12L190.08,286.487H5.25L5.25,286.487z"></path>
	<path style="fill:#FFFFFF;" d="M170.511,9.48v111.982l-74.815-74.81c-10.314,7.67-19.955,16.185-28.888,25.403l123.983,123.983   V4.506C183.921,5.864,177.164,7.547,170.511,9.48z"></path>
</g>
<g>
	<polygon style="fill:#D32030;" points="170.511,306.056 169.8,306.771 170.511,306.771  "></polygon>
	<polygon style="fill:#D32030;" points="190.084,286.487 190.794,286.487 190.794,285.773  "></polygon>
	<polygon style="fill:#D32030;" points="281.229,196.737 280.545,196.737 280.545,197.425  "></polygon>
	<polygon style="fill:#D32030;" points="171.21,176.457 170.511,175.754 170.511,176.457  "></polygon>
	<polygon style="fill:#D32030;" points="190.794,196.037 190.794,196.737 191.494,196.737  "></polygon>
</g>
<g>
	<path style="fill:#252F6C;" d="M300.825,411.764v53.091c25.381-7.105,49.045-18.305,70.211-32.897l-57.526-57.526   C308.913,390.583,307.231,398.933,300.825,411.764z"></path>
	<path style="fill:#252F6C;" d="M313.812,108.471l62.799-62.799C354.05,29.15,328.456,16.559,300.824,8.818v54.538   C308.21,78.146,308.831,89.384,313.812,108.471z"></path>
	<path style="fill:#252F6C;" d="M427.029,377.984c15.815-21.275,28.141-45.29,36.147-71.213h-107.36L427.029,377.984z"></path>
	<path style="fill:#252F6C;" d="M465.887,176.457c-7.188-27.318-19.143-52.676-34.898-75.192l-75.2,75.192H465.887z"></path>
</g>
<g>
	<path style="fill:#E7E7E7;" d="M327.638,290.5l16.275,16.275l77.903,77.903c1.769-2.214,3.526-4.42,5.217-6.69l-71.213-71.213   h107.36c2.046-6.638,3.784-13.41,5.25-20.284H329.16C328.932,289.367,327.911,287.643,327.638,290.5z"></path>
	<path style="fill:#E7E7E7;" d="M311.352,120.348l70.607-70.615c-1.769-1.372-3.541-2.737-5.348-4.061l-62.799,62.799   C314.463,110.954,310.746,117.805,311.352,120.348z"></path>
	<path style="fill:#E7E7E7;" d="M300.825,58.992V8.814c-6.645-1.862-13.41-3.44-20.284-4.727v24.476   C288.088,36.745,294.853,47.022,300.825,58.992z"></path>
	<path style="fill:#E7E7E7;" d="M326.041,196.737h144.195c-1.171-6.866-2.599-13.635-4.345-20.284H355.793l75.2-75.192   C423.6,90.7,415.384,80.768,406.409,71.565l-84.702,84.694C323.988,171.622,325.009,180.544,326.041,196.737z"></path>
	<path style="fill:#E7E7E7;" d="M310.088,371.002l60.952,60.959c10.138-6.982,19.685-14.753,28.593-23.189l-80.173-80.177   C316.901,343.423,313.865,357.745,310.088,371.002z"></path>
	<path style="fill:#E7E7E7;" d="M280.545,442.301v27.28c6.873-1.279,13.635-2.865,20.284-4.727v-53.091   C294.853,423.738,288.088,434.13,280.545,442.301z"></path>
</g>
<path style="fill:#D71F28;" d="M321.707,156.259l84.694-84.694c-7.625-7.831-15.8-15.119-24.446-21.832l-66.55,66.561  C318.363,128.657,319.706,142.808,321.707,156.259z"></path>
<g>
	<path style="fill:#D32030;" d="M225.019,0.292C228.965,0.101,232.899,0,236.836,0C232.876,0,228.935,0.101,225.019,0.292z"></path>
	<path style="fill:#D32030;" d="M236.836,473.68c-3.938,0-7.872-0.108-11.81-0.299C228.942,473.579,232.876,473.68,236.836,473.68z"></path>
	<path style="fill:#D32030;" d="M236.836,473.68c14.943,0,29.535-1.447,43.708-4.099v-27.28   C268.103,455.786,253.549,473.68,236.836,473.68z"></path>
</g>
<g>
	<path style="fill:#D71F28;" d="M470.232,196.737H327.911c1.885,29.704,1.657,60.249-0.681,89.75h141.2   c3.418-16.017,5.25-32.613,5.25-49.643C473.68,223.164,472.461,209.784,470.232,196.737z"></path>
	<path style="fill:#D71F28;" d="M327.638,290.5c-1.316,13.994-5.901,24.898-8.182,38.099l80.173,80.173   c7.932-7.517,15.347-15.557,22.183-24.094l-77.9-77.907L327.638,290.5z"></path>
</g>
<path style="fill:#D32030;" d="M280.545,30.324V4.091C266.376,1.447,251.784,0,236.836,0C253.549,0,268.103,16.843,280.545,30.324z"></path>
<g>
	<path style="fill:#29337A;" d="M300.825,422.007c6.406-12.834,11.899-27.609,16.499-43.757l-16.499-16.499V422.007z"></path>
	<path style="fill:#29337A;" d="M319.377,102.906c-4.989-19.087-11.166-36.439-18.552-51.229v69.773L319.377,102.906z"></path>
</g>
<g>
	<path style="fill:#FFFFFF;" d="M332.234,295.092c0.269-2.857,0.512-5.725,0.744-8.605h-9.349L332.234,295.092z"></path>
	<path style="fill:#FFFFFF;" d="M300.825,121.451V51.674c-5.976-11.97-12.737-22.254-20.284-30.429v129.906l40.735-40.735   c-0.613-2.543-1.257-5.034-1.9-7.517L300.825,121.451z"></path>
	<path style="fill:#FFFFFF;" d="M281.229,196.737h52.429c-1.028-16.192-2.666-32.123-4.944-47.482L281.229,196.737z"></path>
	<path style="fill:#FFFFFF;" d="M280.545,452.432c7.547-8.182,14.308-18.459,20.284-30.429v-60.256l16.499,16.499   c3.784-13.264,6.959-27.434,9.525-42.261l-46.307-46.304L280.545,452.432L280.545,452.432z"></path>
</g>
<path style="fill:#E51D35;" d="M280.545,452.432V289.681l46.304,46.307c2.277-13.205,4.069-26.899,5.381-40.896l-8.605-8.605h9.349  c2.337-29.502,2.565-60.047,0.681-89.75h-52.429l47.482-47.482c-2.001-13.455-4.476-26.469-7.434-38.836l-40.728,40.735V21.248  C268.103,7.763,253.549,0,236.836,0c-3.938,0-7.872,0.101-11.817,0.292c-11.649,0.583-23.073,2.016-34.225,4.215v191.531  L66.808,72.055c-7.618,7.861-14.704,16.237-21.189,25.089l79.313,79.313l20.291,20.284H3.448C1.227,209.784,0,223.164,0,236.844  c0,17.034,1.84,33.626,5.25,49.643h184.834L70.847,405.724c7.808,7.67,16.121,14.813,24.921,21.349l95.023-95.023v137.116  c11.151,2.199,22.583,3.631,34.232,4.215c3.938,0.191,7.872,0.299,11.81,0.299C253.549,473.68,268.103,465.917,280.545,452.432z"></path>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>						</span>
										<span class="elementor-icon-list-text"></span>
											</a>
									</li>
								<li class="elementor-icon-list-item">
					<a href="https://italiani-a-malta.com/" target="_blank">						<span class="elementor-icon-list-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewBox="0 0 473.684 473.684" style="enable-background:new 0 0 473.684 473.684;" xml:space="preserve">
<path style="fill:#E63026;" d="M315.802,13.535l-27.639,76.632c25.512,84.193,25.512,209.156,0,293.353l27.639,76.624  c91.975-32.523,157.882-120.195,157.882-223.31C473.684,133.735,407.777,46.059,315.802,13.535z"></path>
<g>
	<path style="fill:#E4E4E4;" d="M315.802,90.167V13.535C291.102,4.8,264.536,0.002,236.84,0.002   C273.361,0.002,222.723,123.775,315.802,90.167z"></path>
	<path style="fill:#E4E4E4;" d="M236.84,473.682c27.695,0,54.262-4.798,78.962-13.534v-76.624   C223.658,374.328,236.84,473.682,236.84,473.682z"></path>
</g>
<path style="fill:#359846;" d="M0,236.837C0,340.297,66.355,428.2,158.806,460.461V13.229C66.355,45.49,0,133.393,0,236.837z"></path>
<path style="fill:#EF4C4C;" d="M315.802,90.167V383.52C341.317,299.323,341.317,174.359,315.802,90.167z"></path>
<path style="fill:#F3F4F5;" d="M315.802,383.523V90.167C299.677,36.938,273.361,0.002,236.84,0.002  c-27.351,0-53.592,4.697-78.034,13.227v447.234c24.442,8.53,50.683,13.22,78.034,13.22  C273.361,473.682,299.677,436.746,315.802,383.523z"></path>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>						</span>
										<span class="elementor-icon-list-text"></span>
											</a>
									</li>
								<li class="elementor-icon-list-item">
					<a href="https://brasileirosemmalta.net/" target="_blank">						<span class="elementor-icon-list-icon">
							<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 512 512"><circle cx="256" cy="256" fill="#6da544" r="256"></circle><path d="m256 100.174 211.478 155.826-211.478 155.826-211.478-155.826z" fill="#ffda44"></path><circle cx="256" cy="256" fill="#f0f0f0" r="89.043"></circle><g fill="#0052b4"><path d="m211.478 250.435c-15.484 0-30.427 2.355-44.493 6.725.623 48.64 40.227 87.884 89.015 87.884 30.168 0 56.812-15.017 72.919-37.968-27.557-34.497-69.958-56.641-117.441-56.641z"></path><path d="m343.393 273.06c1.072-5.524 1.651-11.223 1.651-17.06 0-49.178-39.866-89.043-89.043-89.043-36.694 0-68.194 22.201-81.826 53.899 12.05-2.497 24.526-3.812 37.305-3.812 51.717-.001 98.503 21.497 131.913 56.016z"></path></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>						</span>
										<span class="elementor-icon-list-text"></span>
											</a>
									</li>
						</ul>
				</div>
				</div>
        <style>
        #network_navigation {
          position: fixed;
    right: 1%;
    bottom: 2%;
    z-index: 10;
        }

        #network_navigation ul li {
          margin: 5px 0;
        }

        #network_navigation ul li .elementor-icon-list-icon svg {
    margin: var(--e-icon-list-icon-margin,0 calc(var(--e-icon-list-icon-size, 1em) * .25) 0 0);
    width: 32px;
    height: 32px;
}
        </style>

        ';


}


add_shortcode('network-navigation', 'display_network_navigation');



add_filter( 'template_include', 'insert_my_template' );

function insert_my_template( $template )
{
    if ( 'business' === get_post_type() )
        return dirname( __FILE__ ) . '/tpl/single-business.php';

    return $template;
}
