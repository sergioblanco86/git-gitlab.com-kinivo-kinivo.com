<?php
/*
Template Name: Personal Information
*/
?>
<?php get_header(); 

// Retrieve The Post's Author ID
$author_id = $GLOBALS['current_user']->ID;
// Set the image size. Accepts all registered images sizes and array(int, int)
$size = 'thumbnail';

// Get the image URL using the author ID and image size params
$imgURL = get_cupp_meta($author_id, $size);
?>
<div class="contentWeb">
			
			<div class="my-account-content">
				<div class="wrapper wrap">
					<div class="bread">Home / My Account</div>
					<div class="dash">
						<div class="top">
							<?php get_template_part('content','menuresponsive-myaccount'); ?>
						</div>

						<?php get_template_part('content','menu-myaccount'); ?>

						<div class="personal-information">
							<div class="header-btos">
								<a class="button standar-nowidth active" data-content="change-info-content">Account Information</a>
								<a class="button standar-nowidth" data-content="address-book-content">My Address Book</a>
							</div>

							<div class="user-information-content change-info-content">
								<h1>Change your Name, Email Address, or Password</h1>
								<p>If you wish to change the name, email address, or password associated with your Kinivo customer account, you may do so below. For your security, we require that you enter your existing password to edit any of your personal information.</p>
								<?php wc_get_template( 'myaccount/form-edit-account.php' ); ?>
							</div>
							



							<div class="user-information-content address-book-content">
								<h1>Manage Your Address Book</h1>
								<a class="button standar-nowidth green add-address show-modal" data-modal="add-address"> add a new shipping address <span class="more">+</span></a>

								<div class="books">

									<div class="address-block">
										<h2>John Doe <span data-icon="&#xf005" aria-hidden="true" class="fav"></span></h2>
										<p>
											1601 Av Weniu 50 Apt 12 <br />
											Miami, Florida 33126 <br />
											United States <br />
											Phone: (305) 555 5555 <br />
										</p>
										<a class="button green standar-nowidth">Edit</a>
										<a class="trash-can"></a>
									</div>

									<div class="address-block">
										<h2>John Doe <span data-icon="&#xf005" aria-hidden="true"></span></h2>
										<p>
											1601 Av Weniu 50 Apt 12 <br />
											Miami, Florida 33126 <br />
											United States <br />
											Phone: (305) 555 5555 <br />
										</p>
										<a class="button green standar-nowidth">Edit</a>
										<a class="trash-can"></a>
									</div>

									<div class="address-block">
										<h2>John Doe <span data-icon="&#xf005" aria-hidden="true"></span></h2>
										<p>
											1601 Av Weniu 50 Apt 12 <br />
											Miami, Florida 33126 <br />
											United States <br />
											Phone: (305) 555 5555 <br />
										</p>
										<a class="button green standar-nowidth">Edit</a>
										<a class="trash-can"></a>
									</div>

									<?php wc_get_template( 'myaccount/my-address.php' ); ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
<?php get_footer(); ?>