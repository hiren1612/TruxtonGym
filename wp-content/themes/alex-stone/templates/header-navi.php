<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */
$header_phone = alex_stone_get_theme_option('header_phone');
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_normal  
			scheme_<?php echo esc_attr(alex_stone_is_inherit(alex_stone_get_theme_option('menu_scheme')) 
												? (alex_stone_is_inherit(alex_stone_get_theme_option('header_scheme')) 
													? alex_stone_get_theme_option('color_scheme') 
													: alex_stone_get_theme_option('header_scheme')) 
												: alex_stone_get_theme_option('menu_scheme')); ?>">
	<div class="content_wrap">
		<div class="columns_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left column-1_4">
				<?php
				// Logo
				?><div class="sc_layouts_item"><?php
					get_template_part( 'templates/header-logo' );
				?></div>
			</div><?php
			
			// Attention! Don't place any spaces between columns!
			?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left column-3_4">
				<div class="sc_layouts_item">
					<?php
					// Main menu
					$alex_stone_menu_main = alex_stone_get_nav_menu(array(
						'location' => 'menu_main', 
						'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
						)
					);
					if (empty($alex_stone_menu_main)) {
						$alex_stone_menu_main = alex_stone_get_nav_menu(array(
							'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
							)
						);
					}
					alex_stone_show_layout($alex_stone_menu_main);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div><?php if (!empty($header_phone)) { ?><div class="sc_layouts_item">
					<div class="sc_layouts_iconed_text">
						<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon icon-telephone"></span>
						<span class="sc_layouts_item_details sc_layouts_iconed_text_details">
							<span class="sc_layouts_item_details_line2 sc_layouts_iconed_text_line2"><?php alex_stone_show_layout(alex_stone_prepare_macros($header_phone)); ?></span>
						</span>
					</div>
				</div><?php } ?>
			</div>
		</div><!-- /.sc_layouts_row -->
	</div><!-- /.content_wrap -->
</div><!-- /.top_panel_navi -->