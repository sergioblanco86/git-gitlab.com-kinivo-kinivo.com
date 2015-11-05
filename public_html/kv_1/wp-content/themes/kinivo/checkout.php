<?php
/*
Template Name: Check Out
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			
			<div class="shipping-process-content">
				<div class="wrapper wrap">
					<!-- <ul class="shipping-steps">
						<li class="step shippping-information active" data-step="1"></li>
						<li class="arrow-next-step"></li>
						<li class="step billing-information" data-step="2"></li>
						<li class="arrow-next-step"></li>
						<li class="step success" data-step="3"></li>
					</ul> -->
					<h1 class="shipping-box-title checkout-step checkout-step-1">Shipping Proccess</h1>
					<!-- <h1 class="shipping-box-title checkout-step checkout-step-2">Step 2: Shipping Information</h1>
					<h1 class="shipping-box-title checkout-step checkout-step-3">Step 3: Shipping Information</h1> -->
					<div class="shipping-box">
						<!-- <div class="shipping-address-wrap">
							<div class="shipping-addresses">
								<h2>Select Shipping Address</h2>
								<ul>
									<li>
										<h3>John Doe</h3>
										<p>1601 Av Weniu 50 apt 12<br />
										Miami, Florida 33126<br />
										United States<br />
										Phone:(305) 555 5555</p>
										<a href="" class="button grey standar-nowidth">SELECT THIS ADDRESS</a>
									</li>
									<li>
										<h3>John Doe</h3>
										<p>1601 Av Weniu 50 apt 12<br />
										Miami, Florida 33126<br />
										United States<br />
										Phone:(305) 555 5555</p>
										<a href="" class="button grey standar-nowidth">SELECT THIS ADDRESS</a>
									</li>
									<li>
										<h3>John Doe</h3>
										<p>1601 Av Weniu 50 apt 12<br />
										Miami, Florida 33126<br />
										United States<br />
										Phone:(305) 555 5555</p>
										<a href="" class="button grey standar-nowidth">SELECT THIS ADDRESS</a>
									</li>
									<li>
										<h3>John Doe</h3>
										<p>1601 Av Weniu 50 apt 12<br />
										Miami, Florida 33126<br />
										United States<br />
										Phone:(305) 555 5555</p>
										<a href="" class="button grey standar-nowidth">SELECT THIS ADDRESS</a>
									</li>
								</ul>
							</div>

							<div class="or">OR</div>

							<div class="new-shipping-address">
								<div class="wrap-limit">
									<a class="button grey standar with-dropdown">ADD NEW SHIPPING ADDRESS</a>
									<div class="form">
										<fieldset>
											<label for="">Full Name:</label>
											<input type="text">
										</fieldset>
										<fieldset>
											<label for="">Address Line 1:</label>
											<input type="text">
										</fieldset>
										<fieldset>
											<label for="">Address Line 2:</label>
											<input type="text">
										</fieldset>
										<fieldset>
											<label for="">State/Province/Region:</label>
											<input type="text">
										</fieldset>
										<fieldset>
											<label for="">ZIP:</label>
											<input type="text">
										</fieldset>
										<fieldset>
											<label for="">Country:</label>
											<select name="" id="">
												<option value="">United States</option>
												<option value="">United Kingdom</option>
											</select>
										</fieldset>
										<fieldset>
											<label for="">Phone Number:</label>
											<input type="text">
										</fieldset>
									</div>
								</div>
							</div>
						</div>

						<div class="shipping-methods">
							<h1>Select Shipping Methods</h1>
						
								
									<div class="col">
										<div class="radio-button">
											<input type="radio" id="opt1" name="shipping-methods">
										</div>
										<label for="opt1">Standard Shipping: FREE (Order received within 3-8 business days)</label>
									</div>
									<div  class="col">
										<div class="radio-button">
											<input type="radio" id="opt2" name="shipping-methods">
										</div>
										<label for="opt2">Standard Shipping: FREE (Order received within 3-8 business days)</label>
									</div>
								
									<div  class="col">
										<div class="radio-button">
											<input type="radio" id="opt3" name="shipping-methods">
										</div>
										<label for="opt3">2-day Shipping: $ 20.00 (Order received within 3 business days)</label>
									</div>
									<div  class="col">
										<div class="radio-button">
											<input type="radio" id="opt4" name="shipping-methods">
										</div>
										<label for="opt4">2-day Shipping: $ 20.00 (Order received within 3 business days)</label>
									</div>
								
									<div  class="col">
										<div class="radio-button">
											<input type="radio" id="opt5" name="shipping-methods">
										</div>
										<label for="opt5">Overnight Shipping: $ 33.00 (Order received within 2 business days)</label>
									</div>
									<div  class="col">
										<div class="radio-button">
											<input type="radio" id="opt6" name="shipping-methods">
										</div>
										<label for="opt6">Overnight Shipping: $ 33.00 (Order received within 2 business days)</label>
									</div>
						</div> -->

						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php the_content();?>
						<?php endwhile; else : ?>
							<p>No content.</p>
						<?php endif;?>

						<div class="shipping-actions">
							<div>
								<!-- <a class="button green standar-nowidth checkout-step checkout-step-1 next-step" data-step="2">Ship to this address</a>
								<a class="button green standar-nowidth checkout-step checkout-step-2 next-step" data-step="1">Back</a> -->
							</div>

							<div>
								<b>Have a question?</b>
								<span>call 800-393-2830</span>
							</div>
							<div>
								<a href="">FAQ</a>
								<a href="">Return Policy</a>
							</div>
							<div>
								<a href="">Warranty policy</a>
								<a href="">Live chat and support</a>
							</div>
						</div>
						
					</div>
				</div>
			</div>
<!-- End Content! -->
<?php get_footer(); ?>