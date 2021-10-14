<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

// Header sidebar
$alex_stone_header_name = alex_stone_get_theme_option('header_widgets');
$alex_stone_header_present = !alex_stone_is_off($alex_stone_header_name) && is_active_sidebar($alex_stone_header_name);
if ($alex_stone_header_present) { 
	alex_stone_storage_set('current_sidebar', 'header');
	$alex_stone_header_wide = alex_stone_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($alex_stone_header_name) ) {
		dynamic_sidebar($alex_stone_header_name);
	}
	$alex_stone_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($alex_stone_widgets_output)) {
		$alex_stone_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $alex_stone_widgets_output);
		$alex_stone_need_columns = strpos($alex_stone_widgets_output, 'columns_wrap')===false;
		if ($alex_stone_need_columns) {
			$alex_stone_columns = max(0, (int) alex_stone_get_theme_option('header_columns'));
			if ($alex_stone_columns == 0) $alex_stone_columns = min(6, max(1, substr_count($alex_stone_widgets_output, '<aside ')));
			if ($alex_stone_columns > 1)
				$alex_stone_widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($alex_stone_columns).' widget ', $alex_stone_widgets_output);
			else
				$alex_stone_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($alex_stone_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$alex_stone_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($alex_stone_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'alex_stone_action_before_sidebar' );
				alex_stone_show_layout($alex_stone_widgets_output);
				do_action( 'alex_stone_action_after_sidebar' );
				if ($alex_stone_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$alex_stone_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>