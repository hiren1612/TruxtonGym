<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

if (alex_stone_sidebar_present()) {
	ob_start();
	$alex_stone_sidebar_name = alex_stone_get_theme_option('sidebar_widgets');
	alex_stone_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($alex_stone_sidebar_name) ) {
		dynamic_sidebar($alex_stone_sidebar_name);
	}
	$alex_stone_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($alex_stone_out)) {
		$alex_stone_sidebar_position = alex_stone_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($alex_stone_sidebar_position); ?> widget_area<?php if (!alex_stone_is_inherit(alex_stone_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(alex_stone_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'alex_stone_action_before_sidebar' );
				alex_stone_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $alex_stone_out));
				do_action( 'alex_stone_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>