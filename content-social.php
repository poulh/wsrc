<?php
$social_fields = array("amazon_authors_page", "twitter", "facebook", "website","email");
$social_tooltips = array("%s Amazon Homepage: Proceeds benefit club!", "%s on Twitter", "%s on Facebook", "%s's Website", "Email %s");
$social_icons = array("fa fa-amazon", "fa fa-twitter", "fa fa-facebook", "fa fa-globe", "fa fa-envelope");
$amazonAffiliateTag = 'westsidegop20-20';
$affiliateUrlParam = '?tag=' . $amazonAffiliateTag;
foreach ($social_fields as $idx=>$social_field) {
    $social_tooltip = sprintf($social_tooltips[$idx], $bioName, $affiliateUrlParam);
    $social_url = get_field($social_field, $bioID);
    
    if ($social_url) {
        if ($social_field == "email") {
            //todo: this is a bit hacky
            $social_url = 'mailto:' . $social_url;
        } elseif ($social_field == "amazon_authors_page") {
            //todo also hacky
            $social_url = $social_url . $affiliateUrlParam;
        } ?>
        <a target="0" title="<?php echo $social_tooltip; ?>" href="<?php echo $social_url; ?>">
        <i class="<?php echo $social_icons[$idx] ?> fa-lg"></i>
        </a>
<?php } ?>
<?php } ?>
