<?php
/*
Template Name: About Us
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<div class="banner about-us-banner" style="background-image:url(<?php echo $image[0]?>);">
				<div class="about-us-intro">
					<img src="<?php echo get_template_directory_uri(); ?>/img/misc/logo-medium.png" alt="Kinivo"/>
					<p>
						<?php echo get_post_meta($post->ID,"intro",true); ?>
					</p>
				</div>
			</div>
			<div class="about-us-content">
				<div class="containerRight">
					<div class="containerLeft">
						<div class="left">
							<h1>About Us</h1>
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<?php the_content();?>
							<?php endwhile; else : ?>
								<p>No content.</p>
							<?php endif;?>
						</div>
						<div class="right">
							<h1>Featured Press Links</h1>
							<ul class="press-links">
								<?php
									$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
									query_posts(array(
									   'posts_per_page' => '4',
								       'paged'          => $paged,
								       'cat'            => '6'
									));
								?>
								<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
									<?php 
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
									?>
									<li>
										<a target="blank" href="<?php echo get_post_meta($post->ID,"link",true); ?>"><img src="<?php echo $image[0]; ?>" alt=""></a>
										<div class="press-info">
											<h1><a target="blank" href="<?php echo get_post_meta($post->ID,"link",true); ?>"><?php echo get_the_title(); ?></a></h1>
											<span class="date"><?php the_time("F d - Y"); ?></span>
											<?php echo the_content(); ?>
										</div>
									</li>
								<?php endwhile; else : ?>
									<li>
										No Press Articles.
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="about-us-instagram">
				<h1>Kinivo on Instagram</h1>
				<div id="kinivo-instagram" >
					<ul class="instagram-feed slides">
						<?php 
							function fetchData($url){
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_TIMEOUT, 20);
								$result = curl_exec($ch);
								curl_close($ch); 
								return $result;
							}

							$result = fetchData("https://api.instagram.com/v1/users/".$GLOBALS['instagram_id']."/media/recent/?access_token=625163348.5b9e1e6.07687dd958374a929dad088a0c57cb55&count=14");

							$result = json_decode($result);
							foreach ($result->data as $post) {
								 if(empty($post->caption->text)) {
								   // Do Nothing
								 }
								 else {
								    ?>
										<li>
											<a target="blank" href="<?php  echo $post->link;?>"><img src="<?php  echo $post->images->low_resolution->url;?>" width="252" height="252"></a>
											<div class="info">
												<span class="hashtag">#Kinivo</span>
												<span class="date"><?php echo htmlentities(date("F j - Y", $post->caption->created_time)) ?></span>
											</div>
										</li>
								    <?php
								 }
							}
						 ?>
					</ul>
				</div>
				<span class="icon" data-icon="&#xf16d" aria-hidden="true"></span>
				<p class="foot-instragram-text"><?php wp_reset_query(); echo get_post_meta($post->ID,"instagram_bottom_text",true); ?></p>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>