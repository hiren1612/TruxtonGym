<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_args = get_query_var('alex_stone_logo_args');

// Site logo
$alex_stone_logo_image  = alex_stone_get_logo_image(isset($alex_stone_args['type']) ? $alex_stone_args['type'] : '');
$alex_stone_logo_text   = alex_stone_is_on(alex_stone_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$alex_stone_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($alex_stone_logo_image) || !empty($alex_stone_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '#' : esc_url(home_url('/')); ?>"><?php
		if (!empty($alex_stone_logo_image)) {
			$alex_stone_attr = alex_stone_getimagesize($alex_stone_logo_image);
			echo '<img src="'.esc_url($alex_stone_logo_image).'" alt="'. esc_attr(basename($alex_stone_logo_image)).'"'.(!empty($alex_stone_attr[3]) ? sprintf(' %s', $alex_stone_attr[3]) : '').'>';
		} else {
			alex_stone_show_layout(alex_stone_prepare_macros($alex_stone_logo_text), '<span class="logo_text">', '</span>');
			alex_stone_show_layout(alex_stone_prepare_macros($alex_stone_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>