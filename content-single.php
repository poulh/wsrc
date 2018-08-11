<?php
/**
 * Content Single.
 *
 * @package parallax-one
 */
?>
<!-- A2: content-single.php Displays a single post -->
<article id="post-<?php the_ID();?>" <?php post_class(
 'content-single-page'
);?>>

    <header class="entry-header single-header">
        <?php
$postCategories = get_the_category() ?? [];
$postCategory = empty($postCategories) ? null : $postCategories[sizeof($postCategories) - 1]; //this is an object
$parentCategory = empty($postCategories) ? null : $postCategories[0]; //this is an object
$postName = $postCategory ? $postCategory->name : "Page";
$postSlug = $postCategory ? $postCategory->slug : "page";
$parentSlug = $parentCategory ? $parentCategory->slug : "page";

$now = new DateTime();
$upcoming = $now->format("Y-m-d") <= get_the_date("Y-m-d");
$dayNumber = get_the_date('j');
$abbreviation = $dayNumber. 'th';
$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($dayNumber %100) >= 11 && ($dayNumber%100) <= 13) {
    // do nothing
} else {
    $abbreviation = $dayNumber. $ends[$dayNumber % 10];
}
$shortDate = get_the_date('M') . " " . $abbreviation . ", '" . get_the_date("y");
$longDate = get_the_date('F') . " " . $abbreviation . ", " . get_the_date("Y");


$postBios = [];
$displayBioContent = false;
if( $postSlug == "event" ) {
    $postBios = get_field('bios');
    if( gettype( $postBios) == "string" ) { //this is dumb. if there isn't a value it doesn't return null but an empty string
        $postBios = [];
    }

    $displayBioContent = true;
} elseif( $parentSlug == "bio" ) {
    $postBios = [ get_the_ID() ];
}
$numBios = sizeof( $postBios );


?>
<?php if(!is_single() ) { ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
<?php } ?>

<?php
                 if (has_post_thumbnail()) {
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


    $upcomingEvent = $now->format("Y-m-d") <= get_the_time("Y-m-d");
    if ($postSlug == "event") {
        $dateText = "Past Event";
        $backgroundColor = $upcomingEvent ? "background-color-primary" : "background-color-alt";
        // if now is before the event's date ( including day of )
        if ($upcomingEvent || !is_single()) {
            $dateText = $shortDate;

        } ?>
            <div class="img-ribbon ribbon-upper-left <?php echo $backgroundColor ?> color-white">
                <?php echo $dateText ?>
            </div>


            <?php $soldOut = get_field("sold_out");
        if ($soldOut) {
            ?>

            <div class="img-ribbon ribbon-upper-right background-color-primary color-white">Sold Out!</div>
            <?php
        } ?>



            <?php
    } ?>

        </div>


        <?php
} ?>


                    <h2 itemprop="headline" class="entry-title single-title">
<?php
                                                 if( !is_single() && !is_archive()) { echo $postName . ": "; }
the_title();
?>
</h2>



<?php if(!is_single() ) { ?>
                          </div>
                </a>
<?php } ?>


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
<?php is_single() ? the_content() : the_excerpt();?>

                              <?php if( $parentSlug == "bio" ) { ?>

<?php  $bioID = get_the_ID(); $bioName = get_the_title();  include( locate_template( 'content-social.php', false, false ) ); ?>

<?php } ?>


        <?php
                              if( is_single() && $postSlug == "event" ) {
        $purchaseUrl = get_field("purchase_url");
        $memberCost = get_field("member_price");
        $guestCost = get_field("guest_price");
        $doorCost = get_field("door_price");
        $hasCost = $memberCost || $guestCost || $doorCost;
        $canPurchaseOnline = $hasCost && $purchaseUrl && !$soldOut && $upcomingEvent;
        if ($canPurchaseOnline) {
            ?>
        <a target="0" href="<?php echo $purchaseUrl; ?>">Purchase Tickets</a>
        <?php
        }
         ?>


        <!-- Event Meta -->
        <div class="row">
            <div class="col-md-12 entry-content" style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin; margin-top:20px;">
                <div class="col-md-4">
                    <div class="event_meta_block">
                        <div class="event_meta_heading" style="padding-bottom:5px;">
                            <strong>Details</strong>
                        </div>
                        <dl>
                            <dt>Date:</dt>
                            <dd>
                                <?php echo $longDate;?>
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
                            <?php $eventUrl = get_field('website');
                            if ($eventUrl) {
                                ?>
                            <dt>
                                Website:
                            </dt>
                            <dd>
                                <a target="0" href="<?php echo $eventUrl; ?>">Visit Website*</a>
                            </dd>
                            <dd>
                                <h6>*Not affiliated with the club.</h6>
                            </dd>

                            <?php
                            } ?>


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
if ($venues) {
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
    }
}?>

                    </dl>
                </div>
                <div class="col-md-4">
                    <?php
            $organizers = get_field('organizers');
            if ($organizers) {
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
                            <?php $organizerName = get_the_title($organizerID);
                        echo $organizerName;
                        $organizerEmail = get_field("email", $organizerID);
                        if ($organizerEmail) {
                            //todo: there is a bug here. if 'get_the_title()' includs an ampersand (&) the mailto will cut off the rest of the string.
                            //however, str_replace does not work as it seems wordpress stores the & as &amp;.  searching/replacing that string also doesn't work. its very frustrating.
                            //spent an hour on it and am giving up. PH
                            $emailUrl = 'mailto:' . $organizerEmail . '?subject=' . get_the_title(); ?>
                            <a href="<?php echo $emailUrl; ?>">
                                <i class="fa fa-envelope"></i>
                            </a>

                            <?php
                        } ?>
                        </dd>

                        <?php

                        if ($hasCost) {
                            ?>
                        <dt>Cost:
                        </dt>
                        <dd>
                            <?php echo($memberCost ? "Member Price: $" . $memberCost : ""); ?>
                            <br>
                            <?php echo($guestCost ? "Guest Price: $" . $guestCost : ""); ?>
                            <br>
                            <?php echo($doorCost ? "Door Price: $" . $doorCost : ""); ?>
                            <?php
                                     if ($canPurchaseOnline) {
                                         ?>
                            <dd>
                                <a target="0" href="<?php echo $purchaseUrl; ?>">Purchase Tickets</a>
                            </dd>
                            <?php
                                     } ?>
                        </dd>
                        <?php
                        } ?>


                        <?php
                    } ?>
                    </dl>
                    <?php
                }
            }?>
                </div>
            </div>
        </div>
        <!-- End Event Meta -->

<?php } ?>

<?php if(is_single()) { ?>
        <!-- Bio Section -->

<?php if( $displayBioContent && ($numBios > 0 ) ) { ?>
        <h4 itemprop="headline" class="entry-title single-title">
            Related
            <?php echo($numBios > 1 ? "Biographies" : "Biography"); ?>
        </h4>
        <div class="colored-line-left"></div>

<?php } ?>
        <div class="clearfix"></div>

        <div style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin; margin-top:20px;">
            <!-- div class="col-md-12 entry-content" -->


            <?php foreach ($postBios as $bioID) {
                    $bioImageID = get_post_thumbnail_id($bioID);
                    $bioImageUrl = wp_get_attachment_image_src($bioImageID, 'parallax-one-post-thumbnail-big', true);
                    $bioPost = get_post($bioID);
                    $bioName = $bioPost->post_title;
                    ?>
            <div class="bio-container" style="margin-top:30px; margin-bottom:30px; ">
<?php if($displayBioContent) { ?>
                <div class="row bio-content-container">
                               <a href="<?php echo get_post_permalink($bioID); ?>">
                               <div class="col-md-4">

                        <picture itemscope itemprop="image">

                            <img src="<?php echo esc_url($bioImageUrl[0]); ?>">

                        </picture>
                        <dl>
                            <dt>
                                <strong>
                                    <?php echo $bioName; ?>
                                </strong>
                            </dt>

                          <?php  include( locate_template( 'content-social.php', false, false ) ); ?>

                        </dl>
                    </div>
                               </a>        
                    <div class="col-md-8">

<?php echo apply_filters( 'the_excerpt', $bioPost->post_content ); ?>
                    </div>
                                                      <?php } ?>
                </div>
                <!-- End Individual Bio -->
                <?php
                $amazonIdStr = get_field("amazon_ids", $bioID);
                    if ($amazonIdStr) {
                        $amazonIds = explode("\n", $amazonIdStr);
                        $numAmazonIds = sizeof($amazonIds);
                        if ($numAmazonIds > 0) {
                            ?>
                <div class="row bio-book-container" style="margin-top:20px; margin-bottom:10px;">
                    <div class="col-md-12">
                        <strong> Click to buy
<?php echo $bioPost->post_title; ?>&apos;s <!-- &apos; is an apostrophe... it messes up my code editor to use the real one -->
                            <?php if ($numAmazonIds > 1) {     ?>
                            books
                            <?php
                            } else {
                                ?>
                            book
                            <?php
                            } ?>
                            from Amazon.</strong>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($amazonIds as $amazonId) {
                            ?>
                        <div class="col-md-2">
                            <a target="0" href="https://www.amazon.com/gp/product/<?php echo $amazonId ?>?tag=westsidegop20-20">
                                <img src="<?php echo " https://images-na.ssl-images-amazon.com/images/P/ " . $amazonId . ".jpg
                                 " ; ?>">
                            </a>
                        </div>
                        <?php
                        } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Proceeds from each purchase benefit the club!</h6>
                    </div>
                </div>
                <?php
                        }
                    } ?>
            </div>
            <?php
                } // end foreach $bioID?>
            <!-- /div -->

            <?php


         ?>
            <!-- End Bio Section -->

<?php } ?>

        </div>
        <!-- .entry-content -->

        <footer class="entry-footer">
<?php //parallax_one_entry_footer();?>
        </footer>
        <!-- .entry-footer -->
</article>
<!-- #post-## -->
