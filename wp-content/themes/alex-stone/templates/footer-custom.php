<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */

$alex_stone_footer_scheme =  alex_stone_is_inherit(alex_stone_get_theme_option('footer_scheme')) ? alex_stone_get_theme_option('color_scheme') : alex_stone_get_theme_option('footer_scheme');
$alex_stone_footer_id = str_replace('footer-custom-', '', alex_stone_get_theme_option("footer_style"));
if ((int) $alex_stone_footer_id == 0) {
	$alex_stone_footer_id = alex_stone_get_post_id(array(
												'name' => $alex_stone_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUT_PT') ? TRX_ADDONS_CPT_LAYOUT_PT : 'cpt_layouts'
												)
											);
}
$alex_stone_footer_meta = get_post_meta($alex_stone_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($alex_stone_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($alex_stone_footer_id))); 
						if (!empty($alex_stone_footer_meta['margin']) != '') 
							echo ' '.esc_attr(alex_stone_add_inline_css_class('margin-top: '.esc_attr(alex_stone_prepare_css_value($alex_stone_footer_meta['margin'])).';'));
						?> scheme_<?php echo esc_attr($alex_stone_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('alex_stone_action_show_layout', $alex_stone_footer_id);
	?>
</footer><!-- /.footer_wrap -->
