<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */
$alex_stone_header_css = $alex_stone_title_image = '';
if (empty($alex_stone_title_image)) 
	$alex_stone_title_image = alex_stone_get_theme_option( 'title_image' );

// Page (category, tag, archive, author) title

if ( alex_stone_need_page_title() ) {
	alex_stone_sc_layouts_showed('title', true);
	alex_stone_sc_layouts_showed('postmeta', false);
	?>
	<div class="top_panel_title scheme_dark default_image<?php if ($alex_stone_title_image!='') echo ' '.esc_attr(alex_stone_add_inline_css_class('background-image: url('.esc_url($alex_stone_title_image).') !important;'));?>">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$alex_stone_blog_title = alex_stone_get_blog_title();
							$alex_stone_blog_title_text = $alex_stone_blog_title_class = $alex_stone_blog_title_link = $alex_stone_blog_title_link_text = '';
							if (is_array($alex_stone_blog_title)) {
								$alex_stone_blog_title_text = $alex_stone_blog_title['text'];
								$alex_stone_blog_title_class = !empty($alex_stone_blog_title['class']) ? ' '.$alex_stone_blog_title['class'] : '';
								$alex_stone_blog_title_link = !empty($alex_stone_blog_title['link']) ? $alex_stone_blog_title['link'] : '';
								$alex_stone_blog_title_link_text = !empty($alex_stone_blog_title['link_text']) ? $alex_stone_blog_title['link_text'] : '';
							} else
								$alex_stone_blog_title_text = $alex_stone_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($alex_stone_blog_title_class); ?>"><?php
								$alex_stone_top_icon = alex_stone_get_category_icon();
								if (!empty($alex_stone_top_icon)) {
									$alex_stone_attr = alex_stone_getimagesize($alex_stone_top_icon);
									?><img src="<?php echo esc_url($alex_stone_top_icon); ?>" alt="<?php echo esc_attr(basename($alex_stone_top_icon)); ?>" <?php if (!empty($alex_stone_attr[3])) alex_stone_show_layout($alex_stone_attr[3]);?>><?php
								}
								echo wp_kses($alex_stone_blog_title_text, 'alex_stone_kses_content');
							?></h1>
							<?php
							if (!empty($alex_stone_blog_title_link) && !empty($alex_stone_blog_title_link_text)) {
								?><a href="<?php echo esc_url($alex_stone_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($alex_stone_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'alex_stone_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>