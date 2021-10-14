<div class="front_page_section front_page_section_woocommerce<?php
			$alex_stone_scheme = alex_stone_get_theme_option('front_page_woocommerce_scheme');
			if (!alex_stone_is_inherit($alex_stone_scheme)) echo ' scheme_'.esc_attr($alex_stone_scheme);
			echo ' front_page_section_paddings_'.esc_attr(alex_stone_get_theme_option('front_page_woocommerce_paddings'));
		?>"<?php
		$alex_stone_css = '';
		$alex_stone_bg_image = alex_stone_get_theme_option('front_page_woocommerce_bg_image');
		if (!empty($alex_stone_bg_image)) 
			$alex_stone_css .= 'background-image: url('.esc_url(alex_stone_get_attachment_url($alex_stone_bg_image)).');';
		if (!empty($alex_stone_css))
			echo " style=\"{$alex_stone_css}\"";
?>><?php
	// Add anchor
	$alex_stone_anchor_icon = alex_stone_get_theme_option('front_page_woocommerce_anchor_icon');	
	$alex_stone_anchor_text = alex_stone_get_theme_option('front_page_woocommerce_anchor_text');	
	if ((!empty($alex_stone_anchor_icon) || !empty($alex_stone_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_woocommerce"'
										. (!empty($alex_stone_anchor_icon) ? ' icon="'.esc_attr($alex_stone_anchor_icon).'"' : '')
										. (!empty($alex_stone_anchor_text) ? ' title="'.esc_attr($alex_stone_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner<?php
			if (alex_stone_get_theme_option('front_page_woocommerce_fullheight'))
				echo ' alex_stone-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$alex_stone_css = '';
			$alex_stone_bg_mask = alex_stone_get_theme_option('front_page_woocommerce_bg_mask');
			$alex_stone_bg_color = alex_stone_get_theme_option('front_page_woocommerce_bg_color');
			if (!empty($alex_stone_bg_color) && $alex_stone_bg_mask > 0)
				$alex_stone_css .= 'background-color: '.esc_attr($alex_stone_bg_mask==1
																	? $alex_stone_bg_color
																	: alex_stone_hex2rgba($alex_stone_bg_color, $alex_stone_bg_mask)
																).';';
			if (!empty($alex_stone_css))
				echo " style=\"{$alex_stone_css}\"";
	?>>
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$alex_stone_caption = alex_stone_get_theme_option('front_page_woocommerce_caption');
			$alex_stone_description = alex_stone_get_theme_option('front_page_woocommerce_description');
			if (!empty($alex_stone_caption) || !empty($alex_stone_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($alex_stone_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo !empty($alex_stone_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($alex_stone_caption, 'alex_stone_kses_content');
					?></h2><?php
				}
			
				// Description (text)
				if (!empty($alex_stone_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo !empty($alex_stone_description) ? 'filled' : 'empty'; ?>"><?php
						echo wpautop(wp_kses($alex_stone_description, 'alex_stone_kses_content'));
					?></div><?php
				}
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs"><?php 
				$alex_stone_woocommerce_sc = alex_stone_get_theme_option('front_page_woocommerce_products');
				if ($alex_stone_woocommerce_sc == 'products') {
					$alex_stone_woocommerce_sc_ids = alex_stone_get_theme_option('front_page_woocommerce_products_per_page');
					$alex_stone_woocommerce_sc_per_page = count(explode(',', $alex_stone_woocommerce_sc_ids));
				} else {
					$alex_stone_woocommerce_sc_per_page = max(1, (int) alex_stone_get_theme_option('front_page_woocommerce_products_per_page'));
				}
				$alex_stone_woocommerce_sc_columns = max(1, min($alex_stone_woocommerce_sc_per_page, (int) alex_stone_get_theme_option('front_page_woocommerce_products_columns')));
				echo do_shortcode("[{$alex_stone_woocommerce_sc}"
									. ($alex_stone_woocommerce_sc == 'products' 
											? ' ids="'.esc_attr($alex_stone_woocommerce_sc_ids).'"' 
											: '')
									. ($alex_stone_woocommerce_sc == 'product_category' 
											? ' category="'.esc_attr(alex_stone_get_theme_option('front_page_woocommerce_products_categories')).'"' 
											: '')
									. ($alex_stone_woocommerce_sc != 'best_selling_products' 
											? ' orderby="'.esc_attr(alex_stone_get_theme_option('front_page_woocommerce_products_orderby')).'"'
											  . ' order="'.esc_attr(alex_stone_get_theme_option('front_page_woocommerce_products_order')).'"' 
											: '')
									. ' per_page="'.esc_attr($alex_stone_woocommerce_sc_per_page).'"' 
									. ' columns="'.esc_attr($alex_stone_woocommerce_sc_columns).'"' 
									. ']');
			?></div>
		</div>
	</div>
</div>