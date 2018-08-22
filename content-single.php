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
$postName = $postCategory ? $postCategory->name .": " : ""; // if no category its a page. we don't put the word 'Page: ' in front of these results on searches because it looks dumb
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
$displayBioExcerpt = false;
$displayBioBox = false;
$isEvent = $postSlug == "event";
if( $isEvent ) {
    $postBios = get_field('bios');
    if( gettype( $postBios) == "string" ) { //this is dumb. if there isn't a value it doesn't return null but an empty string
        $postBios = [];
    }

    $displayBioExcerpt = true;
    $displayBioBox = true; // we display the 'bio box' on an event if it has a bio... regardless of if it has books
} elseif( $parentSlug == "bio" ) {
    $tmpID = get_the_ID();
    $postBios = [ $tmpID ];

    //if we are looking at a bio we only display the 'bio box' if that bio has books. so we have to peak in to see now to do that
    $tmpAmazonIdStr = get_field("amazon_ids", $tmpID);
    if ($tmpAmazonIdStr) {
        $tmpAmazonIds = explode("\n", $tmpAmazonIdStr);
        foreach($tmpAmazonIds as $tmpAmazonId ) {
            $tmpAmazonId = trim($tmpAmazonId );
            if( empty($tmpAmazonId)) {
                continue;
            }
            $displayBioBox = true;
            break;
        }
    }
}
$numBios = sizeof( $postBios );
$isSinglePost = is_single(); // single post means its the actual posts page as opposed to a search or 'category/archive' page

?>
<?php if(!$isSinglePost ) { ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
<?php } ?>

<?php
$postHasFeaturedImage = has_post_thumbnail();
                 if ($postHasFeaturedImage) {
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
    if ($isEvent) {
        $dateText = "Past Event";
        $backgroundColor = $upcomingEvent ? "background-color-primary" : "background-color-alt";
        // if now is before the event's date ( including day of )
        if ($upcomingEvent || !$isSinglePost) {
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

                                                 if( !$isSinglePost && !is_archive()) { echo $postName; } // !is_archive() tells us we are on a search. on searches we display the 'post type' ( event, bio, etc )
the_title();

?>
</h2>



<?php
    // this </a> is the end of the hyperlink. we make the whole image/title a link to the event/bio on category/search pages
    if(!$isSinglePost ) { ?>

                </a>
<?php } ?>


        <div class="colored-line-left"></div>
        <div class="clearfix"></div>


    </header>
    <!-- .entry-header -->

    <div itemprop="text" class="entry-content">
<?php
//if we are on a search/category/archive page for an event and the event doesn't have an image
//we display the date ( which is normally a ribbon on the image )
//after the title. on sigle pages the event is in the event box so no need to display it twice
if($isEvent && !$isSinglePost && !$postHasFeaturedImage) {
    echo  $longDate;
}


                              $isSinglePost ? the_content() : the_excerpt();?>

                              <?php if( $parentSlug == "bio" ) { ?>

<?php  $bioID = get_the_ID(); $bioName = get_the_title();  include( locate_template( 'content-social.php', false, false ) ); ?>

<?php } ?>


        <?php
                              if( $isSinglePost && $isEvent ) {
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
<?php $venueField = 'venue';    include( locate_template( 'content-venue.php', false, false ) ); ?>
                            </div>
                <div class="col-md-4">
<?php       $organizerField = 'organizers';                include( locate_template( 'content-organizer.php', false, false ) ); ?>
<?php
                        if ($hasCost) {
                            ?>
                                                               <dl>
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
                    </dl>
<?php
                        } ?>


                    

                </div>
            </div>
        </div>
        <!-- End Event Meta -->

             <!-- After Event Meta -->
<?php if( get_field('after_event') ) { ?>

             <div class="row">
            <div class="col-md-12 entry-content" style="background-color:rgb(250,250,250); border-color:rgb(238,238,238); border-style:solid;border-width:thin; margin-top:20px;">
            <div class="col-md-4">
            <div class="event_meta_block">
            <div class="event_meta_heading" style="padding-bottom:5px;">
                                  <strong>After Event<?php if(get_field('after_not_affiliated')) { echo "*"; } ?></strong>
            <dl><dd><?php echo get_field('after_title'); ?></dd></dl> 

<?php if(get_field('after_not_affiliated')) { ?>
                                       <dl>
                                       <dd>
                                       <h6>*Not affiliated with the club.</h6>
                                       </dd>
                                       </dl>
<?php } ?>
            </div>
            </div>            </div>
      <div class="col-md-4">
<?php       $venueField = 'after_venue';                include( locate_template( 'content-venue.php', false, false ) ); ?>
            </div>
                        <div class="col-md-4">
<?php       $organizerField = 'after_organizers';                include( locate_template( 'content-organizer.php', false, false ) ); ?>
            </div>
            </div>            </div>
<?php } ?>
              <!-- After Event Meta -->
<?php } ?>

<?php if($isSinglePost) { ?>
        <!-- Bio Section -->

<?php if( $displayBioBox && ($numBios > 0 ) ) { ?>
                                                <?php if($displayBioExcerpt) { ?>
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
<?php if($displayBioExcerpt) { ?>
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

<?php
                               //this is a hack. i can't get get_the_excerpt() to work so i looked up how it works and made my own :-(
                               $bioWords = explode(" ",$bioPost->post_content);
                               if(count($bioWords) > 55 ) {
                                   $bioWords = array_slice($bioWords,0,55);
                                   array_push($bioWords,"[&hellip;]");
                               }

                               echo implode(" ", $bioWords); ?>                          <a href="<?php echo get_post_permalink($bioID); ?>">    <i class="fa fa-info-circle"></i></a>

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
                <div class="row bio-ebook-container" style="margin-top:20px; margin-bottom:10px;">
                    <div class="col-md-12">
                        <strong>Click to buy
<?php echo $bioPost->post_title; ?>&apos;s <!-- &apos; is an apostrophe... it messes up my code editor to use the real one -->
                            <?php if ($numAmazonIds > 1) {     ?>
                            books
                            <?php
                            } else {
                                ?>
                            book
                            <?php
                            } ?>
                            from Amazon, or browse all of our books in the club's <a href="/book-store">Book Store

 <i class="fa fa-book"></i></a>  </strong>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($amazonIds as $amazonId) {
$amazonId = trim($amazonId);
if( empty($amazonId) ) { continue; }
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
<?php } ?>

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
