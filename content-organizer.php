<?php if( $organizerField && !empty($organizerField)) { ?>

<?php
          $organizers = get_field($organizerField);
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
<?php
    $contactName = get_the_title($organizerID);
    echo $contactName; ?>
    <br>
    
<?php $contactID = $organizerID; $contactSubject = get_the_title();
    include( locate_template( 'content-contact-info.php', false, false ) ); ?>
    </dd>    
<?php
} ?>

</dl>
<?php
    }
}?>

<?php } ?>
