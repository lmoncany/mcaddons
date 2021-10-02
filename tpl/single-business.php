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

<?php
while ( have_posts() ) :
	the_post();



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
		$phone =  get_post_meta(get_the_ID(), 'phone', TRUE);
		$cover_image =  get_post_meta(get_the_ID(), 'cover_image', TRUE);
	?>

<main <?php post_class( 'elementor-page-118' ); ?> role="main">

	<header id="hero" class="elementor-section elementor-top-section elementor-element elementor-element-f964efb">


			</header>

			<section class="infobar">
					<ul class="list-inline">
						<?php if ( $website_url != null & $status != 'member' ) : ?>
	 				 	<li><a href="<?php echo $website_url; ?>">Website</a></li>
	 				 	<?php endif; ?>

						<?php if ( $phone != null & $status != 'partner' ) : ?>
	 				 	<li><a href=" <?php echo $phone; ?>">Phone</a></li>
	 				 	<?php endif; ?>

						<?php if ( $email_address__for_customer_ != null  & $status != 'partner' ) : ?>
						<li><a href="mailto:<?php echo $email_address__for_customer_; ?>">Contact</a></li>
						<?php endif; ?>

					</ul>
			</section>


			<section class="elementor-section elementor-top-section elementor-element elementor-element-41213314 elementor-section-boxed ang-section-padding-initial elementor-section-height-default elementor-section-height-default" data-id="41213314" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
								<div class="elementor-container elementor-column-gap-default">
									<div class="elementor-row">

		<aside class="">
			<div class="bloc__card">
				<?php the_post_thumbnail(); ?>
				<h1 class="heading-title"><?php the_title(); ?></h1>

				<p><?php echo $you_catch_phrase; ?></p>


				<p>
				<a href="#" class="elementor-button-link elementor-button elementor-size-xs <?php echo $status; ?>" role="button">
					<?php echo $status; ?>
					</a>
				</p>


				 <?php if ( $opening_hours ) : ?>
				 <p class="heading-red">Opening hours</p>
				 <p><?php echo $opening_hours; ?></p>
				 <?php endif; ?>

				 	<div class="social">

					<?php if ( $facebook_url_copy ) : ?>
					 <a href="	<?php echo $facebook_url_copy; ?>" target="_blank" class="elementor-button-link elementor-button elementor-size-sm" role="button">
					 Facebook
					 </a>
					 <?php endif; ?>

					 <?php if ( $instagram_url ) : ?>
						<a href="	<?php echo $instagram_url; ?>" target="_blank" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						Instagram
						</a>
						<?php endif; ?>

					 <?php if ( $linkedin_link ) : ?>
						<a href="	<?php echo $linkedin_link ?>" target="_blank" class="elementor-button-link elementor-button elementor-size-sm" role="button">
						LinkedIn
						</a>
					<?php endif; ?>
				</div>

			</div>



			<div class="bloc__card">
					 <h4 class="bloc__card--title">Location</h4>
				<?php echo do_shortcode('[gmap-location]'); ?>
			</div>

			<?php if ( $status != 'partner' && $status != 'member' ) : ?>
				<div class="bloc__card">
						 <h4 class="bloc__card--title">Contact</h4>
					<?php echo do_shortcode('[elementor-template id="3683"]'); ?>
				</div>
			<?php endif; ?>




		</aside>
		<section id="content">


			<?php if ( $description ) : ?>
				<div class="bloc__content">
					<h3>Who are you?</h3>
						<p><?php echo $description; ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $what_you_do ) : ?>
				<div class="bloc__content">
					<h3>What are you doing?</h3>
						<p><?php echo $what_you_do; ?></p>
				</div>
			<?php endif; ?>


			<div class="bloc__content">
				<h3>My clients</h3>
					<?php echo do_shortcode('[client-reviews]'); ?>
			</div>


			<div class="bloc__content">
				<h3>My Pictures</h3>
						<?php echo do_shortcode('[remote-gallery]'); ?>
			</div>

			<?php if ( $download_link ) : ?>
				<div class="bloc__content">
					<h3>More informations</h3>
						<p><?php echo $download_link; ?></p>
				</div>
			<?php endif; ?>


		</section>


				</div>
			</div>
		</section>


</main>

	<?php
endwhile;


get_footer();
?>

<style>

.elementor-kit-8 button, .elementor-kit-8 input[type="button"], .elementor-kit-8 input[type="submit"], .elementor-kit-8 .elementor-button.elementor-button {
    background-color: #e7e7e7;
}


<?php
$cover_image =  get_post_meta(get_the_ID(), 'cover_image', TRUE);
$cover_image_url = wp_get_attachment_image_src($cover_image, 'full');
if ($cover_image != null) {
	$cover_image_url = wp_get_attachment_image_src($cover_image, 'full');
	$cover_image_url = $cover_image_url[0];
} else {
	$cover_image_url = "https://malta-communities.com/wp-content/uploads/2021/07/malta_itravelling12132-1.jpg";

}

 ?>

#hero {
min-height: 300px;
background : url('<?php echo $cover_image_url; ?>') center/cover fixed;
}


/*  info bar section 2  */
.infobar {
	background-color: transparent;
    background-image: linear-gradient(
-90deg, var( --e-global-color-51a48a0 ) 0%, #25B1AA 54%);
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
	width: calc(30%);
	text-align: center;
	margin: 0 10px;
	margin-top: -150px;
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

section#content {
	width: calc(70%);
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
}

</style>
