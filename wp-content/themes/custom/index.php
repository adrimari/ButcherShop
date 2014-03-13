<?php 
get_header(); 
get_sidebar('left');
?>
				<article class="middleSection">
						<!--<div class="clear">
							<img src="./includes/images/stars.png" alt="star division">
						</div>-->
						<section class="main-content">
							<?php 
							if (is_front_page()) {
								?><img src="<?php echo (get_template_directory_uri()); ?>/includes/images/raw-steak.jpg" alt= "Steak Dish" width=100%>
								<section class="slider" >
							<ul id="slideshow">
								<li>
									<article class="boxContainer">
									<!-- test -->
										<dl>
											<div class="smallBox">
												<dt>Lorem Ipsum</dt>
												<dd>
													<img src="<?php echo (get_template_directory_uri()); ?>/includes/images/buns.jpg" alt= "Steak Dish" width=100%>
												</dd>
												<dd>
													Lorem ipsum dolor sit amet, qui possit aeterno discere ad, ea mel semper ceteros, ius ei tantas deleniti disputando. Cu omnis everti nam, et legere imperdiet signiferumque vim, usu ne malis prodesset similique. 
												</dd>
												<dd><a class="readMore" href="#">Read more about Lorem Ipsum</a></dd>

											</div>
											<div class="smallBox">
												<dt>Mei an meis</dt>
												<dd>
													<img src="<?php echo (get_template_directory_uri()); ?>/includes/images/veggies.jpg" alt= "Steak Dish" width=100%>
												</dd>
												<dd>Ad porro errem repudiare pri. Mei an meis euripidis reprehendunt. Mea te legimus appellantur. Ei eos tale reque veritus.
												</dd>
												<dd><a class="readMore" href="#">Read more about Lorem Ipsum</a></dd>

											</div>
											<div class="smallBox">
												<dt>errem repudiare</dt>
												<dd>
													<img src="<?php echo (get_template_directory_uri()); ?>/includes/images/stir-fry.jpg" alt= "Steak Dish" width=100%>
												</dd>
												<dd>Pertinax philosophia duo ut, nemore commodo ei vim, id sit ponderum pertinax. Posse debet ponderum ut vim, an per ullum dolor. At est altera erroribus mnesarchum. Sea ei mediocrem persecuti, at per virtute iuvaret nonumes. Has an tamquam salutandi eloquentiam.
												</dd>
												<dd><a class="readMore" href="#">Read more about Lorem Ipsum</a></dd>
											</div>
										</dl>
									<div class="clear"></div>
										<!-- <h2>Testing</h2> -->
									</article>
								</li>
								<li>
									<img src="<?php echo (get_template_directory_uri()); ?>/includes/images/raw-steak.jpg" alt= "Steak Dish" width=100%>
								</li>
							</ul>
						</section><?php
							}else{
								if (have_posts()) :
									while (have_posts()) : the_post();
									the_content();
									endwhile;
								endif;
							}?>
						</section>
					</article>

<?php get_sidebar('right');?>
</div>
<div class="clear dot"></div>
<?php get_footer(); ?>
