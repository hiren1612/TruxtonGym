<?php
/**
 * The template to display the Structured Data Snippets
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.30
 */

// Structured data snippets
if (alex_stone_is_on(alex_stone_get_theme_option('seo_snippets'))) {
	?><div class="structured_data_snippets">
		<meta itemprop="headline" content="<?php the_title_attribute(); ?>">
		<meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
		<meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('Y-m-d')); ?>">
		<div itemscope itemprop="publisher" itemtype="//schema.org/Organization">
			<meta itemprop="name" content="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
			<meta itemprop="telephone" content="">
			<meta itemprop="address" content="">
			<?php
			$alex_stone_logo_image = alex_stone_get_retina_multiplier(2) > 1 
								? alex_stone_get_theme_option( 'logo_retina' )
								: alex_stone_get_theme_option( 'logo' );
			if (!empty($alex_stone_logo_image)) {
				?><meta itemprop="logo" itemtype="//schema.org/logo" content="<?php echo esc_url($alex_stone_logo_image); ?>"><?php
			}
			?>
		</div>
	</div>
	<?php
}
?>