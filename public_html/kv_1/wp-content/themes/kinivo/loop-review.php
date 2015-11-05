			<div class="reviews">
				<ul class="rslides slides2">
					<?php
						$args = array(
							'post_type' => 'review',
							'showposts' => '8',
						);

						$the_query = new WP_Query( $args );
					?>
					<?php if( have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<li>
							<ul>
								<?php
									for($i=1;$i<=5;$i++){
										if($i<=get_field('qualification')){
											echo '<li><span data-icon="&#xf005" aria-hidden="true" class="color"></span></li>';
										}else{
											echo '<li><span data-icon="&#xf005" aria-hidden="true"></span></li>';
										}
									}
								?>
							</ul>
							<p>“<?php the_field('content'); ?>”</p>
							<p><a href="<?php if( get_field('link') != '' ){ the_field('link'); }else{ echo 'javascript:void(0)';} ?>" target="blank"><?php the_title(); ?></a></p>
						</li>
					<?php endwhile; else : ?>
						<li>No Reviews.</li>
					<?php endif;?>
				</ul>
			</div>