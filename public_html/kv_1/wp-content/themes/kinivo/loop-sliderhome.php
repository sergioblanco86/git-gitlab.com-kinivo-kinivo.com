			<div class="banner">
				<ul class="rslides slides1">
					<?php
						$args = array(
							'post_type' => 'home_slider',
							'showposts' => '5',
						);
						$the_query = new WP_Query( $args );
					?>
					<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<li style="background-image: url('<?php echo get_field('image'); ?>');">
							<p><span class="bold"><?php echo get_field('caption_top'); ?></span> <br/><?php echo get_field('caption_bottom'); ?></p>
						</li>
					<?php endwhile; else : ?>
						<li>No Reviews.</li>
					<?php endif;?>
				</ul>
			</div>