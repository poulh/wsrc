<?php
/**
 * SECTION: LATEST NEWS
 *
 * @package parallax-one
 */

$parallax_one_frontpage_animations = get_theme_mod( 'parallax_one_enable_animations', false );

//$parallax_number_of_posts_visible          = get_option( 'posts_per_page' );
$parallax_number_of_posts_visible          = wp_is_mobile() ? PHP_INT_MAX : 4; // a little cheat. on mobile we want to show all future posts becaue the arrows look awkward

$args                              = array(
	'category_name' => 'event', 
	'orderby' => 'post_date',
	'order' => 'ASC',
	'date_query' => array('column' => 'post_date', 'after' => '-1 days'),
	'ignore_sticky_posts' => true,
    'meta_query' => array(
        array('key' => '_thumbnail_id', // we only want posts with thumbnails as no thumbnail indicates reserved date but no info
              'compare' => 'EXISTS')
    )
);

$parallax_latestnews_cat = parallax_latest_news_cat();

if ( ! empty( $parallax_latestnews_cat ) ) :
	$args['cat'] = $parallax_latestnews_cat;
endif;

$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
	$parallax_one_upcoming_events_title = get_theme_mod( 'parallax_one_upcoming_events_title', esc_html__( 'Upcoming Events', 'parallax-one' ) );
	$parallax_one_upcoming_events_title = apply_filters( 'parallax_one_translate_single_string', $parallax_one_upcoming_events_title, 'Upcoming Events Section' );

	if ( $parallax_number_of_posts_visible > 0 ) {
		?>
		<?php parallax_hook_news_before(); ?>
		<section class="brief timeline" id="UpcomingEvents" role="region" aria-label="<?php esc_html_e( 'Latest blog posts', 'parallax-one' ); ?>">
			<?php parallax_hook_news_top(); ?>
			<div class="section-overlay-layer">
				<div class="container">
					<div class="row">

						<!-- TIMELINE HEADING / TEXT  -->
						<?php
						if ( ! empty( $parallax_one_upcoming_events_title ) ) {
							echo '<div class="col-md-12 timeline-text text-left"><h2 class="text-left dark-text">' . esc_attr( $parallax_one_upcoming_events_title ) . ' <span class="read-more" style="font-size:24px"><a href="category/event/?event=upcoming"><i class="fa fa-arrow-circle-right"></i></a></span></h2><div class="colored-line-left"></div></div>';
						} elseif ( is_customize_preview() ) {
							echo '<div class="col-md-12 timeline-text text-left paralax_one_only_customizer"><h2 class="text-left dark-text "></h2><div class="colored-line-left "></div></div>';
						}
						?>

						<div class="parallax-slider-whole-wrap">
<?php if( $the_query->found_posts > $parallax_number_of_posts_visible ) { ?>

							<div class="controls-wrap">
								<button class="control_next icon icon-arrow-carrot-down"><span class="screen-reader-text"><?php esc_attr_e( 'Post slider navigation: Down', 'parallax-one' ); ?></span></button>
								<button class="control_prev fade-btn icon icon-arrow-carrot-up"><span class="screen-reader-text"><?php esc_attr_e( 'Post slider navigation: Up', 'parallax-one' ); ?></span></button>
							</div>
<?php } ?>
							<!-- TIMLEINE SCROLLER -->
							<div itemscope itemtype="http://schema.org/Blog" id="parallax_slider" class="col-md-12 timeline-section">
								<ul class="vertical-timeline" id="timeline-scroll">

									<?php
										$tz = new DateTimeZone( get_option('timezone_string') );
										$i_latest_posts = 0;
									while ( $the_query->have_posts() ) :
										$the_query->the_post();

										$upcoming_event = get_post_time('U',true) >= current_time('U',1);
										$upcoming_event = false; /* turn off upcoming for now as they are all upcoming */
										/* i want to see all the posts on mobile or desktop */
                                        if ( $i_latest_posts % $parallax_number_of_posts_visible == 0 ) {
                                            echo '<li>';
                                        }

										$categories = get_the_category();
										$has_category = !empty($categories);
										$the_title = the_title('','',false); /*false means just return the title. two empty strings are prefix and suffix*/
										$post_title = $the_title;
										?>
										<?php ;/* translators: %s is post name */ ?>
										<div itemscope itemprop="blogPosts" itemtype="http://schema.org/BlogPosting" id="post-<?php the_ID(); ?>" class="timeline-box-wrap" title="<?php printf( esc_html__( '%s', 'parallax-one' ), $post_title ); ?>">
												<div datetime="<?php the_time( 'Y-m-d\TH:i:sP' ); ?>" title="<?php the_time( _x( 'l, F j, Y, g:i a', 'post time format', 'parallax-one' ) ); ?>" class="entry-published date small-text strong">
												<?php if( $upcoming_event ) { ?> <i style="color:red;" class="fa fa-exclamation"></i>

 <?php } ?>
									
												<?php 		the_date('D M j g:iA');?>
												
													
												</div>
												<div itemscope itemprop="image" class="icon-container white-text">
													<a href="<?php the_permalink(); ?>" title="<?php $post_title; ?>">
												<?php

												if ( has_post_thumbnail() ) :
													the_post_thumbnail( 'parallax-one-post-thumbnail-latest-news' );
													else :
													?>
																<img src="<?php echo parallax_one_make_protocol_relative_url( parallax_get_file( '/images/no-thumbnail-latest-news.jpg' ) ); ?>" width="150" height="150" alt="<?php $post_title; ?>">
															<?php
														endif;
													?>
												</a>
													</div>
													<div class="info">
												<header class="entry-header">
													<h3 itemprop="headline" class="entry-title">
													<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo $post_title; ?></a>
														</h3>
													</header>
													<div itemprop="description" class="entry-content entry-summary">
														<?php the_excerpt(); ?>
														<?php ;/* translators: %s is screen reader post name */ ?>
														<a href="<?php the_permalink(); ?>" title="<?php $post_title; ?>" class="read-more"><?php printf( esc_html__( 'Read more %s', 'parallax-one' ), '<span class="screen-reader-text">  ' . $post_title . '</span>' ); ?></a>
													</div>
													</div>
												</div>

											<?php
											/* visible posts live between the two <li> tags.
											 * this is the closing tag. we increment the counter first
											 * so that the li shows up on the zero-ith and 4th. 
											 * if we have less than $parallax_number_of_posts_visible the functions
											 * below add in the ending </li> tag*/
											$i_latest_posts++;
                                            if ( $i_latest_posts % $parallax_number_of_posts_visible == 0 ) {
                                                echo '</li>';
                                            }
										endwhile;
									wp_reset_postdata();
									?>
									</ul>
								</div>
							</div><!-- .parallax-slider-whole-wrap -->
						</div>
					</div>
				</div>
				<?php parallax_hook_news_bottom(); ?>
			</section>
			<?php parallax_hook_news_after(); ?>
	<?php
	} // End if().
} // End if(). ?>
