<?php if( $venueField && !empty($venueField)) { ?>
<div class="event_meta_heading" style="padding-bottom:5px;">
                                     <strong>Venue</strong>
                                          </div>
                                                <dl>
<?php
                                                     $venues = get_field($venueField);
if ($venues) {
    if (sizeof($venues) > 0) {
        $venueID = $venues[0];
        $address = get_field('address', $venueID);
        $address2 = get_field('address2', $venueID);
        $city = get_field('city', $venueID);
        $state = get_field('state', $venueID);
        $zip_code = get_field('zip_code', $venueID);
        $combined_address = $address . ' ' . $city . ' ' . $state . ' ' . $zip_code;
        $google_maps_url = "https://www.google.com/maps/place/" . $combined_address;
        $apple_maps_url = "https://maps.apple.com/?q=" . $combined_address; ?>
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
    Maps: <a target="0" href=" <?php echo $google_maps_url ?> "><i class="fa fa-google"></i></a> <a  href=" <?php echo $apple_maps_url ?> "><i class="fa fa-apple"></i></a>

<?php
} ?>

</dd>

<?php
    }
}?>

</dl>
<?php } ?>