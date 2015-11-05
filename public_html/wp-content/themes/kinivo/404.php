<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * 
 */
get_header(); ?>

<div class="contentWeb">
	<div class="blog-content post-content">
		<div class="wrapper wrap">
				<div class="blog-post-row">
					<div class="right" style="text-align:center;">
						<P>
						<?php

							$phrases = [];
							array_push($phrases, "Error 404: Page dropped and broken by someone else.");
							array_push($phrases, "Error 404: Page stuck in nostril. Call a doctor.");
							array_push($phrases, "Error 404: Page was missing when I got here.");
							array_push($phrases, "Error 404: Tripped on server cord.");
							array_push($phrases, "Error 404: Page stolen by pterodactyl.");
							array_push($phrases, "Error 404: Page spontaneously combusted.");
							array_push($phrases, "Error 404: Page's waveform collapsed.");
							array_push($phrases, "Error 404: When you think about it, is there really such thing as a \"page,\" man?");
							array_push($phrases, "Error 404: Page annihilated by anti-page. ");
							array_push($phrases, "Error 404: Page is napping. ");
							array_push($phrases, "Error 404: Page stuck on 405 South.");
							array_push($phrases, "Error 404: Page carried away by a swarm of ants.");
							array_push($phrases, "Error 404: I thought you wanted Jimmy Page. My mistake.");
							array_push($phrases, "Error 404: Page trapped in cage.");
							array_push($phrases, "Error 404: Page lost while spelunking.");
							array_push($phrases, "Error 404: Page fell out of plane during barrel roll.");
							array_push($phrases, "Error 404: Page swallowed by whale.");
							array_push($phrases, "Error 404: Page torn asunder by the mighty kracken.");
							array_push($phrases, "Error 404: Page kidnapped by aliens for experimentation.");
							array_push($phrases, "Error 404: Page rustled by banditos.");
							array_push($phrases, "Error 404: Page stampeded off a cliff.");
							array_push($phrases, "Error 404: Page lost in space.");
							array_push($phrases, "Error 404: Page struck iceberg while traversing the North Pacific.");
							array_push($phrases, "Error 404: Page vanished over the Bermuda Triangle.");
							array_push($phrases, "Error 404: Page devoured by a pack of velociraptors.");
							array_push($phrases, "Error 404: Page arrested for a crime it didn't commit.");
							array_push($phrases, "Error 404: Page was a loose cannon that played by its own rules, and was kicked off the force.");
							array_push($phrases, "Error 404: Page traveled through time to a dystopian future.");

							echo $phrases[rand(0,count($phrases)-1)];

?>
						</P>
						<img src="wp-content/themes/kinivo/img/oops.jpg"/>
					</div>
				</div>
		</div>
	</div>
<SCRIPT>
	url = window.location.href;
	if ( url.match(/kinivo.com.Kinivo.BTC450.Bluetooth.Hands.Free-Input.dp.B009NLTW60/i) ){
		window.location.assign("https://kinivo.com/product/btc450-hands-free-bluetooth-car-kit/");
	}

	if ( url.match(/kinivo.com.Kinivo.BTD.400.Bluetooth.4.0.Adapter.dp.B007Q45EF4/i) ){
		window.location.assign("https://kinivo.com/product/btd-400-bluetooth-4-0-usb-adapter/");
	}

	if ( url.match(/kinivo.com.fr.boutique/i) ){
		window.location.assign("https://kinivo.com");
	}
</SCRIPT>

<?php get_footer(); ?>