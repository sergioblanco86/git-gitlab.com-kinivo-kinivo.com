<?php 

// Retrieve The Post's Author ID
$author_id = $GLOBALS['current_user']->ID;
// Set the image size. Accepts all registered images sizes and array(int, int)
$size = 'thumbnail';

// Get the image URL using the author ID and image size params
$imgURL = get_cupp_meta($author_id, $size);

$page_id = get_the_ID();
$permalink = get_permalink( $page_id );

$order_history = get_bloginfo('url').'/order-history/';
$wishlist = get_bloginfo('url').'/wishlist/';
$my_account = get_bloginfo('url').'/my-account/';

if($imgURL == ''){
	$imgURL = get_template_directory_uri().'/img/misc/no-profile-pic.jpg';
}
?>
<ul class="responsive-menu">
	<li class="user">
		<span class="profile-picture" style="background-image: url('<?php echo $imgURL; ?>');"></span>
		<span class="user-name"><?php echo $GLOBALS['current_user']->user_firstname; ?></span>
		<a class="drop-menu"><?php echo get_the_title(); ?></a>
	</li>
	<li>
		<a href="<?php bloginfo('url'); ?>/order-history" <?php if($order_history == $permalink){ echo 'class="active"';} ?> >Order History</a>
	</li>
	<li>
		<a href="<?php bloginfo('url'); ?>/wishlist" <?php if($wishlist == $permalink){ echo 'class="active"';} ?> >My Wishlist</a>
	</li>
	<li>
		<a href="<?php bloginfo('url'); ?>/my-account" <?php if($my_account == $permalink){ echo 'class="active"';} ?> >Personal Information</a>
	</li>
</ul>
<ul class="rest-menu-responsive">
	<li>
		<a href="<?php bloginfo('url'); ?>/order-history" <?php if($order_history == $permalink){ echo 'class="active"';} ?> >Order History</a>
	</li>
	<li>
		<a href="<?php bloginfo('url'); ?>/wishlist" <?php if($wishlist == $permalink){ echo 'class="active"';} ?> >My Wishlist</a>
	</li>
	<li>
		<a href="<?php bloginfo('url'); ?>/my-account" <?php if($my_account == $permalink){ echo 'class="active"';} ?> >Personal Information</a>
	</li>
</ul>