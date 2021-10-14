<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */

// Footer sidebar
$alex_stone_footer_name = alex_stone_get_theme_option('footer_widgets');
$alex_stone_footer_present = !alex_stone_is_off($alex_stone_footer_name) && is_active_sidebar($alex_stone_footer_name);
if ($alex_stone_footer_present) { 
	alex_stone_storage_set('current_sidebar', 'footer');
	$alex_stone_footer_wide = alex_stone_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($alex_stone_footer_name) ) {
		dynamic_sidebar($alex_stone_footer_name);
	}
	$alex_stone_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($alex_stone_out)) {
		$alex_stone_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $alex_stone_out);
		$alex_stone_need_columns = true;
		if ($alex_stone_need_columns) {
			$alex_stone_columns = max(0, (int) alex_stone_get_theme_option('footer_columns'));
			if ($alex_stone_columns == 0) $alex_stone_columns = min(4, max(1, substr_count($alex_stone_out, '<aside ')));
			if ($alex_stone_columns > 1)
				$alex_stone_out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($alex_stone_columns).' widget ', $alex_stone_out);
			else
				$alex_stone_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($alex_stone_footer_wide) ? ' footer_fullwidth' : ''; ?> ">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$alex_stone_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($alex_stone_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'alex_stone_action_before_sidebar' );
				alex_stone_show_layout($alex_stone_out);
				do_action( 'alex_stone_action_after_sidebar' );
				if ($alex_stone_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$alex_stone_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>