<?php //$contactID, $contactName, $contactSubject must be set before this template is loaded
$contactFields = array("email", "phone");
$contactUrls = array("mailto:%s?subject=%s", "tel:%s");
$contactTooltips = array("Email %s", "Call %s");
$contactIcons = array("fa fa-envelope", "fa fa-phone");
foreach ($contactFields as $idx=>$contactField) {

    $contactVal = get_field($contactField, $contactID);
    if ($contactVal) {
        $contactTooltip = sprintf($contactTooltips[$idx], $contactName);
        $contactUrl = sprintf($contactUrls[$idx],$contactVal,$contactSubject);
 ?>
        <a target="0" title="<?php echo $contactTooltip; ?>" href="<?php echo $contactUrl; ?>">
        <i class="<?php echo $contactIcons[$idx] ?>"></i>
        </a>
<?php } ?>
<?php } ?>
<a href="<?php echo get_post_permalink($contactID); ?>">
       <i class="fa fa-info-circle"></i>
               </a>
