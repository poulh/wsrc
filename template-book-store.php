
<?php

$args = array(
    'post_type' => 'post',
	'category_name'		=> 'bio',
    'posts_per_page'	=> -1,
    'meta_query'	=> array(
		'relation'		=> 'AND',
		array(
			'key'	 	=> 'amazon_ids',
			'value'	  	=> '',
			'compare' 	=> '!=',
		)
	),
);


// query
$the_query = new WP_Query( $args ); ?>


<?php if( $the_query->have_posts() ):
$idx = 0;
?>
        <div class="bookshelf--frame">
<?php while( $the_query->have_posts() ) : $the_query->the_post();
$amazonIds = explode("\n",get_field('amazon_ids'));


foreach($amazonIds as $amazonId) { 
    if($idx % 6 == 0 ) { ?>

<?php    }    ?>
    <div class="book-wrapper" style="display:inline-block; position-relative;">
    <a target="0" href="https://www.amazon.com/gp/product/<?php echo $amazonId ?>?tag=westsidegop20-20"> 
    <img alt-text="foo" width="125" height="175" src="<?php echo " https://images-na.ssl-images-amazon.com/images/P/ " . $amazonId . ".jpg" ; ?>">
    </a> 
  </div>

<?php    if($idx % 6 == 5 ) { ?>

<?php    } ?>
<?php $idx = $idx + 1; }
?>


	<?php endwhile; ?>
        </div>
<?php endif; ?>


<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>

