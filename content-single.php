<?php
/**
 * Content Single.
 *
 * @package parallax-one
 */
?>
<?php the_ID()?>
<!-- A2: content-single.php Displays a single post -->
<article id="post-<?php the_ID();?>" <?php post_class(
 'content-single-page'
);?>>

	<header class="entry-header single-header">
		<?php if (has_post_thumbnail()) {
    ?>

		<div class="post-img-wrap">
			<?php
$image_id = get_post_thumbnail_id();
    $image_url_big = wp_get_attachment_image_src($image_id, 'parallax-one-post-thumbnail-big', true);
    $image_url_mobile = wp_get_attachment_image_src($image_id, 'parallax-one-post-thumbnail-mobile', true); ?>
			<picture itemscope itemprop="image">
				<source media="(max-width: 600px)" srcset="<?php echo esc_url($image_url_mobile[0]); ?>">
				<img src="<?php echo esc_url($image_url_big[0]); ?>" alt="<?php the_title_attribute(); ?>">
			</picture>
			<?php
// only show the date box for events
    $parentCategory = get_the_category()[0];
    if ($parentCategory && $parentCategory->slug == "event") {
        ?>
			<div class="post-date entry-published updated">
				<span class="post-date-day">
					<?php the_time('d'); ?>
				</span>
				<span class="post-date-month">
					<?php the_time('M'); ?>
				</span>
			</div>
			<?php
    } ?>

		</div>
		<?php
} ?>
		<?php the_title('<h2 itemprop="headline" class="entry-title single-title">', '</h2>');?>
		<div class="colored-line-left"></div>
		<div class="clearfix"></div>

		<div class="entry-meta single-entry-meta">
			<span class="author-link" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
				<span itemprop="name" class="post-author author vcard">
					<i class="icon-man-people-streamline-user"></i>
					<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" itemprop="url"
					 rel="author">
						<?php the_author();?>
					</a>
				</span>
			</span>
			<time class="post-time posted-on published" datetime="<?php the_time('c');?>"
			 itemprop="datePublished">
				<i class="icon-clock-alt"></i>
				<?php the_time(get_option('date_format'));?>
			</time>
			<a href="<?php comments_link();?>" class="post-comments">
				<i class="icon-comment-alt"></i>
				<?php comments_number(esc_html__('No comments', 'parallax-one'), esc_html__('One comment', 'parallax-one'), esc_html__('% comments', 'parallax-one'));?>
			</a>
		</div>
		<!-- .entry-meta -->
	</header>
	<!-- .entry-header -->

	<div itemprop="text" class="entry-content">
		<?php the_content();?>

		<!-- Event Meta -->
		<div class="col-md-12 entry-content" style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin; margin-top:20px;">
			<div class="col-md-4">
				<div class="event_meta_block">
					<div class="event_meta_heading" style="padding-bottom:5px;">
						<strong>Details</strong>
					</div>
					<dl>
						<dt>Date:</dt>
						<dd>
							<?php echo get_the_date("F j");?>
						</dd>
						<dt>Time:</dt>
						<dd>
							<?php $eventStartDate = new DateTime(get_the_date('Y-m-d H:i:sP'));
                            $eventEndDate = clone($eventStartDate);
                            $hours = get_field('duration');
                            $seconds = $hours * 60 * 60;
                            $spec = "PT". $seconds . "S";
                            $interval = new DateInterval($spec);
                            $eventEndDate->add($interval);
                            $fmt = "g:ia";
                            echo $eventStartDate->format($fmt) . " - " .$eventEndDate->format($fmt);
                             ?>
						</dd>
					</dl>
				</div>
			</div>
			<div class="col-md-4">
				<div class="event_meta_heading" style="padding-bottom:5px;">
					<strong>Venue</strong>
				</div>
				<dl>
					<?php
$venues = get_field('venue');
if (sizeof($venues) > 0) {
    $venueID = $venues[0];
    $address = get_field('address', $venueID);
    $address2 = get_field('address2', $venueID);
    $city = get_field('city', $venueID);
    $state = get_field('state', $venueID);
    $zip_code = get_field('zip_code', $venueID);
    $combined_address = $address . ' ' . $city . ' ' . $state . ' ' . $zip_code;
    $google_url = "https://www.google.com/maps/place/" . $combined_address; ?>
					<dd>
						<?php echo get_the_title($venueID) ?>
					</dd>
					<dd>
						<?php echo $address . "<br>"; ?>
						<?php if ($address2) {
        echo $address2 . "<br>";
    } ?>

						<?php echo $city; ?>
						<?php if ($state) {
        echo ", " . $state;
    } ?>
						<?php echo $zip_code; ?>
						<br>
						<?php if ($address && $city && $state && $zip_code) {
        ?>
						<a target="0" href=" <?php echo $google_url ?> ">+ Google Maps</a>
						<?php
    } ?>

					</dd>

					<?php
}?>

				</dl>
			</div>
			<div class="col-md-4">
				<?php 
            $organizers = get_field('organizers');
            $numOrganizers = sizeof($organizers);
            if ($numOrganizers > 0) {
                ?>
				<div class="event_meta_heading" style="padding-bottom:5px;">
					<strong>
						<?php echo($numOrganizers > 1 ? "Organizers" : "Organizer") ?>
					</strong>
				</div>
				<dl>
					<?php foreach ($organizers as $organizerID) {
                    ?>
					<dd>
						<?php echo get_the_title($organizerID); ?>
					</dd>
					<?php
                } ?>
				</dl>
				<?php
            } ?>
			</div>
		</div>
		<!-- End Event Meta -->

		<!-- Bio Section -->
		<?php 
        $bios = get_field('bios');
        $numBios = sizeof($bios);
        
        if ($numBios > 0) {
            ?>
		<div class="col-md-12 entry-content" style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin; margin-top:20px;">
			<div class="bio-header">
				Related
				<?php echo($numBios > 1 ? "Biographies" : "Biography"); ?>
			</div>
			<?php foreach ($bios as $bioID) {
                $bioImageID = get_post_thumbnail_id($bioID);
                $bioImageUrl = wp_get_attachment_image_src($bioImageID, 'parallax-one-post-thumbnail-big', true); ?>
			<div class="bio-content">
				<div class="col-md-4">
					<picture itemscope itemprop="image">
						<!--source media="(max-width: 600px)" srcset="<?php echo esc_url($image_url_mobile[0]); ?>" -->
						<img src="<?php echo esc_url($bioImageUrl[0]); ?>">
					</picture>
				</div>
				<div class="col-md-8">
					<?php
                //stupid. there isn't a get_the_content(postID) method so i have to reget the post and do stuff.
                $content_post = get_post($bioID);
                $content = $content_post->post_content;
                echo $content; ?>

				</div>
			</div>
			<?php
            } ?>

		</div>
		<?php
        }
         ?>

		<!-- End Bio Section -->

	</div>
	<!-- .entry-content -->

	<footer class="entry-footer">
		<?php parallax_one_entry_footer();?>
	</footer>
	<!-- .entry-footer -->
</article>
<!-- #post-## -->