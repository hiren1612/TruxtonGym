<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */

// Footer menu
$alex_stone_menu_footer = alex_stone_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($alex_stone_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php alex_stone_show_layout($alex_stone_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>