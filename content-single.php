<?php
/**
 * Content Single.
 *
 * @package parallax-one
 */
?>
<!-- A2: content-single.php Displays a single post -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-single-page' ); ?>>
	<?php the_ID() ?>
	<header class="entry-header single-header">
		<?php
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					?>
		<div class="post-img-wrap">
					<?php
					$image_id         = get_post_thumbnail_id();
					$image_url_big    = wp_get_attachment_image_src( $image_id, 'parallax-one-post-thumbnail-big', true );
					$image_url_mobile = wp_get_attachment_image_src( $image_id, 'parallax-one-post-thumbnail-mobile', true );
					?>
					<picture itemscope itemprop="image">
					<source media="(max-width: 600px)" srcset="<?php echo esc_url( $image_url_mobile[0] ); ?>">
					<img src="<?php echo esc_url( $image_url_big[0] ); ?>" alt="<?php the_title_attribute(); ?>">
					</picture>
			<?php 
						// only show the date box for events
						$parentCategory = get_the_category()[0];
						if( $parentCategory && $parentCategory->slug == "event") {
							?>
			<div class="post-date entry-published updated">
					<span class="post-date-day"><?php the_time( 'd' ); ?></span>
					<span class="post-date-month"><?php the_time( 'M' ); ?></span>
				</div>
			<?php
						}
						?>
				
		</div>
					<?php } ?>
		<?php the_title( '<h2 itemprop="headline" class="entry-title single-title">', '</h2>' ); ?>
		<div class="colored-line-left"></div>
		<div class="clearfix"></div>

		<div class="entry-meta single-entry-meta">
			<span class="author-link" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
				<span itemprop="name" class="post-author author vcard">
					<i class="icon-man-people-streamline-user"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" itemprop="url" rel="author"><?php the_author(); ?></a>
				</span>
			</span>
			<time class="post-time posted-on published" datetime="<?php the_time( 'c' ); ?>" itemprop="datePublished">
				<i class="icon-clock-alt"></i><?php the_time( get_option( 'date_format' ) ); ?>
			</time>
			<a href="<?php comments_link(); ?>" class="post-comments">
				<i class="icon-comment-alt"></i><?php comments_number( esc_html__( 'No comments', 'parallax-one' ), esc_html__( 'One comment', 'parallax-one' ), esc_html__( '% comments', 'parallax-one' ) ); ?>
			</a>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div itemprop="text" class="entry-content">
		<?php the_content(); ?>

		<div class="col-md-12 entry-content" style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin;">
			<div class="col-md-4">
				<div class ="event_meta_block">
					<div class = "event_meta_heading" style="padding-bottom:5px;">
						<strong>Details</strong>						
					</div>				
					<dl>
						<dt>Date:</dt>
						<dd><?php the_date('F j') ?></dd>
						<dt>Time:</dt>
						<dd><?php the_date('F j') ?></dd>
					</dl>
				</div>
			</div>
			<div class="col-md-4">
					<div class = "event_meta_heading" style="padding-bottom:5px;">
						<strong>Venue</strong>						
					</div>				
					<dl>
					<?php $venues = get_field('venue');
							if(sizeof($venues) > 0 ) {
								$venueID = $venues[0];
								$address = get_field('address',$venueID);
								$address2 = get_field('address2',$venueID);
								$city = get_field('city',$venueID);
								$state = get_field('state', $venueID);
								$zip_code = get_field('zip_code',$venueID);
								$combined_address = $address . ' ' . $city . ' ' . $state . ' ' . $zip_code;
								$google_url = "https://www.google.com/maps/place/" . $combined_address;
								?>
					<dd><?php echo get_the_title($venueID) ?></dd>
					<dd><?php echo $address; ?><br>
						<?php echo $address2; ?><br>
						<?php echo $city; ?>, <?php echo $state; ?> <?php echo $zip_code; ?><br>
						<a target="0" href = " <?php echo $google_url ?> ">+ Google Maps</a>
					</dd>

						<?php } ?>					
					
					</dl>
			</div>
			<div class="col-md-4">
					<!--div class = "event_meta_heading" style="padding-bottom:5px;">
						<strong>Details</strong>						
					</div>				
				<dl>
						<dt>Date:</dt>
						<dd><?php the_date('F j') ?></dd>
						<dt>Time:</dt>
						<dd><?php the_date('F j') ?></dd>
					</dl -->
			</div>
		</div>
		<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'parallax-one' ),
					'after'  => '</div>',
				)
			);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php parallax_one_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
