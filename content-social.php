<?php //$bioID, $bioName, $amazonAffiliateTag must be set before this template is loaded
$socialFields = array("amazon_authors_page", "twitter", "facebook", "website","email");
$socialUrls = array("%s%s", "https://www.twitter.com/%s", "https://www.facebook.com/%s", "%s","mailto:%s");
$socialTooltips = array("%s Amazon Homepage: Proceeds benefit club!", "%s on Twitter", "%s on Facebook", "%s's Website", "Email %s");
$socialIcons = array("fa fa-amazon", "fa fa-twitter", "fa fa-facebook", "fa fa-globe", "fa fa-envelope");
$amazonAffiliateTag = 'westsidegop20-20';
$affiliateUrlParam = $amazonAffiliateTag ? '?tag=' . $amazonAffiliateTag : "";
foreach ($socialFields as $idx=>$socialField) {

    $socialVal = get_field($socialField, $bioID);
    if ($socialVal) {
        $socialTooltip = sprintf($socialTooltips[$idx], $bioName, $affiliateUrlParam);
        $socialUrl = sprintf($socialUrls[$idx],$socialVal,$affiliateUrlParam);
 ?>
        <a target="0" title="<?php echo $socialTooltip; ?>" href="<?php echo $socialUrl; ?>">
        <i class="<?php echo $socialIcons[$idx] ?> fa-lg"></i>
        </a>
<?php } ?>
<?php } ?>
