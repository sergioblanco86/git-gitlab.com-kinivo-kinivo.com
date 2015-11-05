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
					<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/blog-banner-dummy.jpg');">
						<div class="banner-blog-post">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
							<span>John Doe</span>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span>April 24 - 2014</span>
							<a class="banner-blog-button">Read this Post</a>
						</div>
					</li>
					<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/blog-banner-dummy.jpg');">
						<div class="banner-blog-post">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
							<span>John Doe</span>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span>April 24 - 2014</span>
							<a class="banner-blog-button">Read this Post</a>
						</div>
					</li>
					<li style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/blog-banner-dummy.jpg');">
						<div class="banner-blog-post">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
							<span>John Doe</span>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span>April 24 - 2014</span>
							<a class="banner-blog-button">Read this Post</a>
						</div>
					</li>
				</ul>
			</div>

			<div class="blog-content">
				<div class="wrapper wrap">
					<h1>Recent Post</h1>
					
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<?php
							// Retrieve The Post's Author ID
						    $user_id = get_the_author_meta('ID');
						    // Set the image size. Accepts all registered images sizes and array(int, int)
						    $size = 'thumbnail';

						    // Get the image URL using the author ID and image size params
						    $imgURL = get_cupp_meta($user_id, $size);
						?>

						<div class="blog-post-row">
							<div class="left">
								<span class="profile-picture" style="background-image: url('<?php echo $imgURL; ?>');"></span>
							</div>
							<div class="right">
								<h2>John Done</h2>
								<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
								<span class="date">April 24 - 2014</span>
								<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
							</div>
						</div>

					<?php endwhile; else : ?>
						<div class="blog-post-row">
							<div class="right">
								<p>No Posts here</p>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- <div class="blog-post-row">
						<div class="left">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
						</div>
						<div class="right">
							<h2>John Done</h2>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span class="date">April 24 - 2014</span>
							<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
						</div>
					</div>

					<div class="blog-post-row">
						<div class="left">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
						</div>
						<div class="right">
							<h2>John Done</h2>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span class="date">April 24 - 2014</span>
							<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
						</div>
					</div>

					<div class="blog-post-row">
						<div class="left">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
						</div>
						<div class="right">
							<h2>John Done</h2>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span class="date">April 24 - 2014</span>
							<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
						</div>
					</div>

					<div class="blog-post-row">
						<div class="left">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
						</div>
						<div class="right">
							<h2>John Done</h2>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span class="date">April 24 - 2014</span>
							<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
						</div>
					</div>

					<div class="blog-post-row">
						<div class="left">
							<span class="profile-picture" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/misc/prifile-pic-dummy.png');"></span>
						</div>
						<div class="right">
							<h2>John Done</h2>
							<h1>LOREM IPSUM SOME TITLE HERE FOR THE POST</h1>
							<span class="date">April 24 - 2014</span>
							<p>Lorem ipsum dolor sit amet, usu alterum ceteros at. Nobis reprehendunt nam ei, an dico quot inciderint vim. Ut noster nusquam definitiones vim, his ne assueverit cotidieque. Eu aeterno vocibus vim, splendide forensibus constituam ea cum, ut perfecto molestiae sed. Usu postea mollis epicuri no, est ea perfecto patrioque, ex unum nostro vix..</p>
						</div>
					</div> -->

					<div class="blog-pager">
						<a class="prev"></a>
						<span class="pages">Page 1 - 20</span>
						<a class="next"></a>
					</div>

				</div>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>