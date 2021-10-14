<div class="front_page_section front_page_section_blog<?php
			$alex_stone_scheme = alex_stone_get_theme_option('front_page_blog_scheme');
			if (!alex_stone_is_inherit($alex_stone_scheme)) echo ' scheme_'.esc_attr($alex_stone_scheme);
			echo ' front_page_section_paddings_'.esc_attr(alex_stone_get_theme_option('front_page_blog_paddings'));
		?>"<?php
		$alex_stone_css = '';
		$alex_stone_bg_image = alex_stone_get_theme_option('front_page_blog_bg_image');
		if (!empty($alex_stone_bg_image)) 
			$alex_stone_css .= 'background-image: url('.esc_url(alex_stone_get_attachment_url($alex_stone_bg_image)).');';
		if (!empty($alex_stone_css))
			echo " style=\"{$alex_stone_css}\"";
?>><?php
	// Add anchor
	$alex_stone_anchor_icon = alex_stone_get_theme_option('front_page_blog_anchor_icon');	
	$alex_stone_anchor_text = alex_stone_get_theme_option('front_page_blog_anchor_text');	
	if ((!empty($alex_stone_anchor_icon) || !empty($alex_stone_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_blog"'
										. (!empty($alex_stone_anchor_icon) ? ' icon="'.esc_attr($alex_stone_anchor_icon).'"' : '')
										. (!empty($alex_stone_anchor_text) ? ' title="'.esc_attr($alex_stone_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_blog_inner<?php
			if (alex_stone_get_theme_option('front_page_blog_fullheight'))
				echo ' alex_stone-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$alex_stone_css = '';
			$alex_stone_bg_mask = alex_stone_get_theme_option('front_page_blog_bg_mask');
			$alex_stone_bg_color = alex_stone_get_theme_option('front_page_blog_bg_color');
			if (!empty($alex_stone_bg_color) && $alex_stone_bg_mask > 0)
				$alex_stone_css .= 'background-color: '.esc_attr($alex_stone_bg_mask==1
																	? $alex_stone_bg_color
																	: alex_stone_hex2rgba($alex_stone_bg_color, $alex_stone_bg_mask)
																).';';
			if (!empty($alex_stone_css))
				echo " style=\"{$alex_stone_css}\"";
	?>>
		<div class="front_page_section_content_wrap front_page_section_blog_content_wrap content_wrap">
			<?php
			// Caption
			$alex_stone_caption = alex_stone_get_theme_option('front_page_blog_caption');
			if (!empty($alex_stone_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_blog_caption front_page_block_<?php echo !empty($alex_stone_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses($alex_stone_caption, 'alex_stone_kses_content'); ?></h2><?php
			}
		
			// Description (text)
			$alex_stone_description = alex_stone_get_theme_option('front_page_blog_description');
			if (!empty($alex_stone_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_blog_description front_page_block_<?php echo !empty($alex_stone_description) ? 'filled' : 'empty'; ?>"><?php echo wpautop(wp_kses($alex_stone_description, 'alex_stone_kses_content')); ?></div><?php
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_blog_output"><?php 
				if (is_active_sidebar('front_page_blog_widgets')) {
					dynamic_sidebar( 'front_page_blog_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!alex_stone_exists_trx_addons())
						alex_stone_customizer_need_trx_addons_message();
					else
						alex_stone_customizer_need_widgets_message('front_page_blog_caption', 'ThemeREX Addons - Blogger');
				}
			?></div>
		</div>
	</div>
</div>