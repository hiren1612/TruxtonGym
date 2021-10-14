<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

						// Widgets area inside page content
						alex_stone_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					alex_stone_create_widgets_area('widgets_below_page');

					$alex_stone_body_style = alex_stone_get_theme_option('body_style');
					if ($alex_stone_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$alex_stone_footer_style = alex_stone_get_theme_option("footer_style");
			if (strpos($alex_stone_footer_style, 'footer-custom-')===0)
				$alex_stone_footer_style = alex_stone_is_layouts_available() ? 'footer-custom' : 'footer-default';
			get_template_part( "templates/{$alex_stone_footer_style}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(alex_stone_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>