<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */

// Copyright area
$alex_stone_footer_scheme =  alex_stone_is_inherit(alex_stone_get_theme_option('footer_scheme')) ? alex_stone_get_theme_option('color_scheme') : alex_stone_get_theme_option('footer_scheme');
$alex_stone_copyright_scheme = alex_stone_is_inherit(alex_stone_get_theme_option('copyright_scheme')) ? $alex_stone_footer_scheme : alex_stone_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($alex_stone_copyright_scheme); ?>">
	<div class="content_wrap">
		<?php if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') { ?><div class="columns_wrap"><?PHP } ?>
			<?php if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') { ?><div class="column-2_3 sc_layouts_column_align_left sc_layouts_column_icons_position_left"><?PHP } ?>
				<div class="trx_addons_copyright"><?php
					// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
					$alex_stone_copyright = alex_stone_prepare_macros(alex_stone_get_theme_option('copyright'));
					if (!empty($alex_stone_copyright)) {
						// Replace {date_format} on the current date in the specified format
						if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $alex_stone_copyright, $alex_stone_matches)) {
							$alex_stone_copyright = str_replace($alex_stone_matches[1], date(str_replace(array('{', '}'), '', $alex_stone_matches[1])), $alex_stone_copyright);
						}
						// Display copyright
						echo wp_kses_data(nl2br($alex_stone_copyright));
					}
				?></div>
			<?php if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') { ?></div><?PHP } ?><?php if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') { ?><div class="column-1_3 sc_layouts_column_align_right sc_layouts_column_icons_position_left">
			<div class="copyright_text">
					<div class="socials_wrap">
						<?php alex_stone_show_layout($alex_stone_output); ?>
					</div>
				</div>
			</div>
			<?php }	?>
		<?php if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') { ?></div><?php }	?>
	</div>
</div>
