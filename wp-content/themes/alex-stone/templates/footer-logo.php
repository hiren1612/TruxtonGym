<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */

// Logo
if (alex_stone_is_on(alex_stone_get_theme_option('logo_in_footer'))) {
	$alex_stone_logo_image = '';
	if (alex_stone_is_on(alex_stone_get_theme_option('logo_retina_enabled')) && alex_stone_get_retina_multiplier(2) > 1)
		$alex_stone_logo_image = alex_stone_get_theme_option( 'logo_footer_retina' );
	if (empty($alex_stone_logo_image)) 
		$alex_stone_logo_image = alex_stone_get_theme_option( 'logo_footer' );
	$alex_stone_logo_text   = get_bloginfo( 'name' );
	if (!empty($alex_stone_logo_image) || !empty($alex_stone_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($alex_stone_logo_image)) {
					$alex_stone_attr = alex_stone_getimagesize($alex_stone_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($alex_stone_logo_image).'" class="logo_footer_image" alt="'. esc_attr(basename($alex_stone_logo_image)).'"'.(!empty($alex_stone_attr[3]) ? sprintf(' %s', $alex_stone_attr[3]) : '').'></a>' ;
				} else if (!empty($alex_stone_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($alex_stone_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>