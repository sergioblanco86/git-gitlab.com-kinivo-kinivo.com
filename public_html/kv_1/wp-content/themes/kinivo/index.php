<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>
		<!-- Content! -->

		<div class="contentWeb">
			<div class="banner blog">
				<ul class="rslides slides3">
					<?php
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

						query_posts(array(
						   'showposts' => '5',
						   'cat'       => '-6'
						));
					?>

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<?php
							// Retrieve The Post's Author ID
						    $author_id = get_the_author_meta('ID');
						    // Set the image size. Accepts all registered images sizes and array(int, int)
						    $size = 'thumbnail';

						    // Get the image URL using the author ID and image size params
						    $imgURL = get_cupp_meta($author_id, $size);

						    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
						?>

						<li style="background-image: url('<?php echo $image[0]; ?>');">
							<div class="banner-blog-post">
								<span class="profile-picture" style="background-image: url('<?php echo $imgURL; ?>');"></span>
								<span><?php echo ucwords( get_the_author() ); ?></span>
								<h1><?php echo strtoupper( get_the_title() ); ?></h1>
								<span><?php the_time("F d - Y"); ?></span>
								<a class="banner-blog-button" href="<?php echo the_permalink();?>">Read this Post</a>
							</div>
						</li>

					<?php endwhile; else : ?>
						<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/blog-banner-dummy.jpg');">
							<div class="banner-blog-post">
								<span>NO Posts Here</span>
							</div>
						</li>
					<?php endif; 
						wp_reset_query(); 
					?>
				</ul>
			</div>

			<div class="blog-content" id="posts-list">
				<div class="wrapper wrap">
					<h1>Recent Post</h1>
					<?php
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

						query_posts(array(
						   'posts_per_page' => '10',
					       'paged'          => $paged,
					       'cat'            => '-6'
						));
					?>

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						
						<?php
							// Retrieve The Post's Author ID
						    $author_id = get_the_author_meta('ID');
						    // Set the image size. Accepts all registered images sizes and array(int, int)
						    $size = 'thumbnail';

						    // Get the image URL using the author ID and image size params
						    $imgURL = get_cupp_meta($author_id, $size);
						?>

						<div class="blog-post-row">
							<div class="left">
								<span class="profile-picture" style="background-image: url('<?php echo $imgURL; ?>');"></span>
							</div>
							<div class="right">
								<h2><?php echo ucwords( get_the_author() ); ?></h2>
								<h1><a href="<?php echo the_permalink();?>"><?php echo strtoupper( get_the_title() ); ?></a></h1>
								<span class="date"><?php the_time("F d - Y"); ?></span>
								<p>
									<?php
										$this_excerpt = strip_shortcodes( get_the_content() );
										$this_excerpt = strip_tags($this_excerpt);
										if( strlen($this_excerpt) >= 340){
											$this_excerpt = substr($this_excerpt, 0, 340).'...';
										}else{
											$this_excerpt = substr($this_excerpt, 0, 340);
										}
										
										echo $this_excerpt;
									?></p>
							</div>
						</div>

					<?php endwhile; else : ?>
						<div class="blog-post-row">
							<div class="right">
								<p>No Posts here</p>
							</div>
						</div>
					<?php endif; ?>

					<div class="blog-pager">
						<?php
							global $wp_query;
							$pages = $wp_query->max_num_pages;
							$prev = $paged - 1;
							$prev_link = get_bloginfo('url').'/blog/page/'.$prev."#posts-list";
							$next = $paged + 1;
							$next_link = get_bloginfo('url').'/blog/page/'.$next."#posts-list";
						?>
						<?php if($paged > 1){?>
						<a class="prev" href="<?php echo $prev_link; ?>"></a>
						<?php }?>
						<span class="pages">Page <?php echo $paged; ?> - <?php echo $pages; ?></span>
						<?php if($paged < $pages){?>
						<a class="next" href="<?php echo $next_link; ?>"></a>
						<?php }?>
					</div>

				</div>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>