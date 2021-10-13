<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

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
while ( have_posts() ) :
	the_post();






		$cover_image_url = 'https://malta-communities.com/wp-content/uploads/2021/07/malta_itravelling12132-1.jpg';

		$you_catch_phrase = get_post_meta(get_the_ID(), 'you_catch_phrase', TRUE);
		$linkedin_link = get_post_meta(get_the_ID(), 'linkedin_link', TRUE);
		$instagram_url = get_post_meta(get_the_ID(), 'instagram_url', TRUE);
	  $facebook_url_copy = get_post_meta(get_the_ID(), 'facebook_url_copy', TRUE);
		$opening_hours = get_post_meta(get_the_ID(), 'opening_hours', TRUE);
		$status = get_post_meta(get_the_ID(), 'which_option_do_you_want_', TRUE);
		$description = get_post_meta(get_the_ID(), 'describe_who_you_are', TRUE);
		$what_you_do =  get_post_meta(get_the_ID(), 'describe_what_you_do', TRUE);

		$download_link =  get_post_meta(get_the_ID(), 'download_link', TRUE);
		$email_address__for_customer_ =  get_post_meta(get_the_ID(), 'email_address__for_customer_', TRUE);
		$website_url =  get_post_meta(get_the_ID(), 'website_url', TRUE);
		$phone =  get_post_meta(get_the_ID(), 'phone_number__for_customer_', TRUE);
		$cover_image =  get_post_meta(get_the_ID(), 'cover_image', TRUE);
		$shortcode_form = get_post_meta(get_the_ID(), 'shortcode_form', TRUE);
		$business_location =  get_post_meta( get_the_ID(), 'business_address', true);
	?>

<main <?php post_class( 'elementor-page-118' ); ?> role="main">

	<header id="hero" class="elementor-section elementor-top-section elementor-element elementor-element-f964efb">
<?php

// get image url of a post
	$slug = get_post_field( 'post_name', get_post() );
	$url =  'https://malta-communities.com/wp-json/wp/v2/business/?slug=' . $slug;
	$request =  wp_safe_remote_get($url);
	$body = wp_remote_retrieve_body( $request );
	$data = json_decode( $body );
	$cover_url = $data[0]->cover_image_url;
	return var_dump($data);
	echo $cover_url;
	
 ?>

			</header>

			<section class="infobar">
					<ul class="list-inline">
						<?php if ( $website_url != null & $status != 'member' ) : ?>
	 				 	<li><a href="<?php echo $website_url; ?>"><i aria-hidden="true" class="icon icon-link"></i> <?php _e('Website', 'mcaddons'); ?></a></li>

	 				 	<?php endif; ?>

						<?php if ( $phone != null & $status != 'partner' ) : ?>
	 				 	<li><a href="tel:<?php echo $phone; ?>"><i aria-hidden="true" class="icon icon-phone"></i> <?php _e('Phone', 'mcaddons'); ?></a></li>
	 				 	<?php endif; ?>

						<?php if ( $email_address__for_customer_ != null  & $status != 'partner' ) : ?>
						<li><a href="mailto:<?php echo $email_address__for_customer_; ?>"><i aria-hidden="true" class="icon icon-envelope1"></i> Contact</a></li>
						<?php endif; ?>

					</ul>
			</section>


			<section class="elementor-section elementor-top-section elementor-element elementor-element-41213314 elementor-section-boxed ang-section-padding-initial elementor-section-height-default elementor-section-height-default" data-id="41213314" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
								<div class="elementor-container elementor-column-gap-default">
									<div class="elementor-row">

		<aside class="">
			<div class="bloc__card">
				<?php the_post_thumbnail(); ?>

				<h1 class="heading-title"><i aria-hidden="true" class="badge fas fa-check-circle color-<?php echo $status; ?>"></i> <?php the_title(); ?></h1>

				<p><?php echo $you_catch_phrase; ?></p>


				<p>
				<a href="#" class="elementor-button-link elementor-button elementor-size-xs <?php echo $status; ?>" role="button">
					<?php echo $status; ?>
					</a>
				</p>


				 <?php if ( $opening_hours ) : ?>
				 <p class="heading-red"><?php _e('Openning hours', 'mcaddons'); ?></p>
				 <p><?php echo $opening_hours; ?></p>
				 <?php endif; ?>


				 <p class="heading-red"></p>
				 <?php
					/* FIRST
					 * Note: This function only returns results from the default “category” taxonomy. For custom taxonomies use get_the_terms().
					 */
					$categories = get_the_terms( $post->ID, 'category' );
					// now you can view your category in array:
					// using var_dump( $categories );
					// or you can take all with foreach:
					echo '<ul class="list-unstyled">';
					foreach ( $categories as $category ) {
	        echo '<li><a target="_blank" class="text-decoration-none list-group-item list-group-item-action text-dark" href="' . esc_attr( esc_url( get_category_link( $category->term_id ) ) ) . '"><i class="fa fa-tag"></i> ' . $category->name . '</a></li>';
	    	}
					echo '</ul>';
				?>



				 	<div class="social">

					<?php if ( $facebook_url_copy ) : ?>
					 <a href="	<?php echo $facebook_url_copy; ?>" target="_blank" class="" role="button">
						 <i class="fab fa-facebook"></i>

					 </a>
					 <?php endif; ?>

					 <?php if ( $instagram_url ) : ?>
						<a href="	<?php echo $instagram_url; ?>" target="_blank" class="" role="button">
							 <i class="fab fa-instagram"></i>
						</a>
						<?php endif; ?>

					 <?php if ( $linkedin_link ) : ?>
						<a href="	<?php echo $linkedin_link ?>" target="_blank" class="" role="button">
							 <i class="fab fa-linkedin"></i>
						</a>
					<?php endif; ?>
				</div>

			</div>


			<?php if ( $business_location ) : ?>
			<div class="bloc__card bloc__location">
				<div class="title" style="display: flex; align-items:baseline;justify-content: space-between">
					 <h4 class="bloc__card--title"><?php _e('Location', 'mcaddons'); ?></h4>
 						<a href="	<?php echo 'http://maps.google.com/?q='.$business_location['lat'].','.$business_location['lng']; ?>" target="_blank" class="" role="button">
 								Direction
 						 </a>
				 </div>
				<?php  echo do_shortcode('[gmap-location]'); ?>
			</div>
 			<?php endif; ?>

			<?php if ( $status != 'partner' && $status != 'member' ) : ?>
				<div class="bloc__card contact_form">
						 <h4 class="bloc__card--title"><?php _e('Contact', 'mcaddons'); ?></h4>

						 <?php if ( $shortcode_form ) { ?>
							<?php  echo do_shortcode('[elementor-template id="3683"]'); ?>
						<?php } else { ?>
							<p class="no_form">Formulaire indisponible</p>
						<?php } ?>
				</div>
			<?php endif; ?>




		</aside>
		<section id="content">


			<?php if ( $description ) : ?>
				<div class="bloc__content">
					<h3><?php _e('Who are you?', 'mcaddons'); ?></h3>
						<p><?php echo $description; ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $what_you_do ) : ?>
				<div class="bloc__content">
					<h3><?php _e('What are you doing?', 'mcaddons'); ?></h3>
						<p><?php echo $what_you_do; ?></p>
				</div>
			<?php endif; ?>


			<div class="bloc__content bloc__reviews">
				<h3><?php _e('My clients', 'mcaddons'); ?></h3>
					<?php  echo do_shortcode('[client-reviews]'); ?>
			</div>


			<div class="bloc__content">
				<h3><?php _e('My pictures', 'mcaddons'); ?></h3>
				<?php
				$url = site_url();

				if ( $url == 'https://malta-communities.com' ) {

					$images = get_post_meta(get_the_ID(), 'image_upload_1', TRUE);
					$idImages = implode(', ', $images);

					echo do_shortcode( '[gallerie  owl="true" link="none" size="medium" ids="'.$idImages.'"]' );

				} else {
				//	echo do_shortcode('[remote-gallery]');
				}
			?>

				<?php

				?>
			</div>

			<?php if ( $download_link ) : ?>
				<div class="bloc__content">
					<h3><?php _e('More informations', 'mcaddons'); ?></h3>
					<a href="	<?php echo $download_link; ?>" target="_blank" class="btn-dark elementor-button-link elementor-button elementor-size-sm" role="button">
					<?php _e('View', 'mcaddons'); ?>
					</a>
				</div>
			<?php endif; ?>


		</section>


				</div>
			</div>
		</section>


</main>

<style>

.elementor-kit-8 button, .elementor-kit-8 input[type="button"], .elementor-kit-8 input[type="submit"], .elementor-kit-8 .elementor-button.elementor-button {
		background-color: #e7e7e7;
}


		.owl-carousel .item{
				margin: 3px;
		}
		.owl-carousel .item img{
				display: block;
				width: 100%;
				height: auto;
		}



#hero {
min-height: 300px;
background : url('<?php echo $cover_image_url; ?>') center/cover fixed;
}


/*  info bar section 2  */
.infobar {
background: #25B1AA;
min-height: 50px;
text-align: right;
padding: 0 8%;
line-height: 50px;
}


ul.list-inline {
	display: inline-block;
}

ul.list-inline li {
	list-style-type: none;
	color: white;
	display: inline;
	padding: 10px 20px;

}


ul.list-inline li a {
	color: white ;
		font-family: "Rubik", Sans-serif;
		font-weight: 600;
}

/* FIN INFOBAR */



.page-content {
	transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
		z-index: 10;
		background-color: #FBFBFB;
		position: relative;
		padding: 0px 0px 80px 0px;
		width: 100%;
display: -webkit-box;
display: -ms-flexbox;
display: flex;
}


/* sidebar */
aside {
	width: calc(25%);
	text-align: center;
	margin: 0 10px;
	margin-top: -150px;
	background: white;
}

.attachment-post-thumbnail {
	width: 100%;
		max-width: 170px;
		height: 170px;
		border-style: solid;
		border-width: 2px 2px 2px 2px;
		border-color: #CACACA;
		border-radius: 50% 50% 50% 50%;
		text-align: center;
		margin: auto;
}

.heading-red {
			color: var( --e-global-color-accent );
			font-weight: bold;
}

section#content {
	width: calc(75%);
}
*
.badge {
	font-size: 20px;
}

.owl-prev {
		width: 15px;
		height: 100px;
		position: absolute;
		background: none !important;
		top: 40%;
		margin-left: -20px;
		display: block !important;
		border:0px solid black;
}

.owl-next {
		width: 15px;
		height: 100px;
		background: none !important;
		position: absolute;
		top: 40%;
		right: 10px;
		display: block !important;
		border:0px solid black;
}
.owl-prev i, .owl-next i {
	transform: scale(1.2);
		color: #ccc;
		background: black;
		padding: 5px;
		height: 32px;
		width: 32px;
		line-height: 25px;
		border-radius: 50%;
		color: white;
}

.bloc__card {
			background-color: var( --e-global-color-15e42c3 );
					padding: 15px 15px 15px 15px;
					box-shadow: 0px 0px 29px -14px rgb(0 0 0 / 10%);
		transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
		margin-top: 20px;
		margin-bottom: 0px;
}

.bloc__content {
	border-style: solid;
		border-color: #EFEFEF75;
		margin: 25px 10px;
				padding: 25px;
		transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
				border-radius: 20px 20px 20px 20px;
}

.bloc__content p {
	line-height: 2.2em;
}

.owl-carousel .owl-wrapper {
		display: flex !important;
}
.owl-carousel .owl-item img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		max-width: initial;
}


/* headings */
h3 {
	color: var( --e-global-color-accent );
		font-family: "Poppins", Sans-serif;
		font-size: 35px;
		font-weight: 700;
		letter-spacing: -1.44px;
}

.heading-title {
		color: var( --e-global-color-primary );
		font-family: var( --e-global-typography-primary-font-family ), Sans-serif;
		font-weight: var( --e-global-typography-primary-font-weight );
		font-size: 30px;
}

.social {
	display: inline-flex;
	margin-top: 40px;
	margin-bottom: 20px;
}
.social a {
	margin: 5px;
	padding: 5px;
	border-radius: 50%;
	height: 32px !important;

	width: 32px !important;
	display: 	block;
	background:  var( --e-global-color-primary );
	color: white !important;
}

.btn-dark {
	background: var( --e-global-color-accent ) !important;
	color: white !important;
}


.social a:hover {
	background: var( --e-global-color-accent ) !important;
}

.list-unstyled {
	list-style: none;
	margin-left: 0;
	padding-left: 0;
}

.list-unstyled li {
	display: inline-block;
	padding: 3px;
}


/* mobile */
@media screen and (max-width: 600px) {
	aside , section#content {
		width: calc(100%);
		padding:15Px;
	}
}

</style>

	<?php
endwhile;


get_footer();
?>
