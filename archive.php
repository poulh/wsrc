<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package parallax-one
 */

	get_header();
?>

	</div>
	<!-- /END COLOR OVER IMAGE -->
	<?php parallax_hook_header_bottom(); ?>
</header>
<!-- /END HOME / HEADER  -->
<?php parallax_hook_header_after(); ?>

<?php parallax_hook_content_before(); ?>
<div role="main" id="content" class="content-warp">
	<?php parallax_hook_content_top(); ?>
	<div class="container">

		<div id="primary" class="content-area col-md-8 post-list">
			<?php
			echo '<main ';
			if ( have_posts() ) {
				echo ' itemscope itemtype="http://schema.org/Blog" ';
			}
			echo ' id="main" class="site-main" role="main">';

			if ( have_posts() ) {

				echo '<header class="page-header">';
                //for event archives this will prepend 'Events' with Upcoming or Past
                $replaceWith = get_queried_object()->slug == "event" ? get_query_var("event","") : "";
                $archiveTitle = str_replace("Category:", $replaceWith, get_the_archive_title());
                if( $wp_query->post_count > 1 ) {
                    $archiveTitle = $archiveTitle .'s';
                }
                echo '<h2 class="page-title">' . $archiveTitle . '</h2>';
                the_archive_description( '<div class="taxonomy-description">', '</div>' );
				echo '</header>';

				while ( have_posts() ) {
					the_post();
					parallax_hook_entry_before();
					/**
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					// get_template_part( 'content', get_post_format() );
                    get_template_part( 'content', 'single' );
					parallax_hook_entry_after();
				}
                if( (get_queried_object()->slug == "event") && (get_query_var("event") == "next")) {
                    // no navigation
                } else {
                    the_posts_navigation();
                }

			} else {
				get_template_part( 'content', 'none' );
			}
			?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>

	</div>
	<?php parallax_hook_content_bottom(); ?>
</div><!-- .content-wrap -->
<?php parallax_hook_content_after(); ?>
<?php get_footer(); ?>
