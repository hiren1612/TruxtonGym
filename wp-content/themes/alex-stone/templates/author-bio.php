<?php
/**
 * The template to display the Author bio
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */
?>

<div class="author_info author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php 
		$alex_stone_mult = alex_stone_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 102*$alex_stone_mult ); 
		?>
	</div><!-- .author_avatar -->

	<div class="author_description">
		<div class="author_about_title" itemprop="name"><?php echo wp_kses_data(sprintf(__('About Author', 'alex-stone'))); ?></div>
		<h5 class="author_title" itemprop="name"><?php echo wp_kses_data('<span class="fn">'.get_the_author().'</span>'); ?></h5>

		<div class="author_bio" itemprop="description">
			<?php echo wp_kses(wpautop(get_the_author_meta( 'description' )), 'alex_stone_kses_content'); ?>
		</div><!-- .author_bio -->

	</div><!-- .author_description -->

</div><!-- .author_info -->
