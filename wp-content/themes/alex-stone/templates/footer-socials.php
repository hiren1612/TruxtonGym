<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.10
 */


// Socials
if ( alex_stone_is_on(alex_stone_get_theme_option('socials_in_footer')) && ($alex_stone_output = alex_stone_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php alex_stone_show_layout($alex_stone_output); ?>
		</div>
	</div>
	<?php
}
?>