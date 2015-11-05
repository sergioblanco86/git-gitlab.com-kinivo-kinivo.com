<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			

			<div class="blog-content post-content">
				<div class="wrapper wrap">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<h1><?php echo strtoupper( get_the_title() ); ?></h1>
						<div class="post">
							<div class="body">
								<?php echo the_content(); ?>
								<!-- <img src="<?php echo get_template_directory_uri(); ?>/img/misc/products-category-dummy.jpg" align="left" alt="">
								<p>Lorem ipsum dolor sit amet, maiorum dissentias efficiantur vix te, pro enim scripta ullamcorper ne. Ignota noster cu nec. Cu harum reformidans usu, id purto delenit tractatos pro. Vis debet patrioque an, pri ut debet propriae nominavi, mucius virtute ad sed. Munere nominati ea quo.Lorem ipsum dolor sit amet, maiorum dissentias efficiantur vix te, pro enim scripta ullamcorper ne. Ignota noster cu nec. Cu harum reformidans usu, idin autem commodo eruditi vix, quo munere appetere convenire ea. Eu quas justo tempor eam. Ad vel vitae persius, dicat mandamus ut eum, eum 
								An nec alii consul epicuri. Eu sea repudiare signiferumque. Te velit graece audiam per, id tale adipiscing nec. Ex duo tacimates suavitate scribentur, in autem commodo eruditi vix, quo munere appetere convenire ea. Eu quas justo tempor eam. Ad vel vitae persius, dicat mandamus ut eum, eum ubique tamquam at.</p>

								<p>Quod alterum usu ne, audire oportere ut sea. In diam aeque commune mea, munere impedit molestiae ut eam. Qui ipsum tamquam te, dicta minimum usu ea. Vim torquatos disputationi an, et has tractatos persecuti. Eos paulo possim alterum no, partem scripserit ne eum.</p>
								  
								<p>Oportere scriptorem eam et, te mollis postulant honestatis usu. Per eu saepe omittam persecuti. Ex graeco oporteat usu, eam eripuit nominati ad. Quo et alia efficiendi ullamcorper, vel ut voluptua euripidis.</p>

								<p>Has ut latine aliquid percipitur, sit alia choro iracundia eu, pri at facer saperet epicuri. Sint atqui officiis eu vim, denique persequeris id eos, ius option detracto facilisis cu. Pro no facer quando, tota novum tation cu eam. Aeque nemore appellantur mei ut.</p> -->
							</div>
							<div class="reply">
								<?php
									// Retrieve The Post's Author ID
								    $author_id = get_the_author_meta('ID');
								    // Set the image size. Accepts all registered images sizes and array(int, int)
								    $size = 'thumbnail';

								    // Get the image URL using the author ID and image size params
								    $imgURL = get_cupp_meta($author_id, $size);
								?>
								<div class="author">
									<span class="written-by">Written by:</span>
									<span class="profile-picture" style="background-image: url('<?php echo $imgURL; ?>');"></span>
									<span class="author-name"><?php echo ucwords( get_the_author() ); ?></span>
								</div>
								<div class="date"><?php the_date('F d - Y'); ?></div>
								<div class="reply-form">
									<?php comments_template(); ?>
								</div>
							</div>
						</div>
					<?php endwhile; else : ?>
						<div class="blog-post-row">
							<div class="right">
								<p>No Posts here</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>