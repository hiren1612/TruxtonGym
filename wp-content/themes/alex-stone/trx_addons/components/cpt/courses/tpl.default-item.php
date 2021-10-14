<?php
/**
 * The style "default" of the Courses
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_courses');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);

if (!empty($args['slider'])) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}
?>
<div class="sc_courses_item trx_addons_hover trx_addons_hover_style_links">
	<?php if (has_post_thumbnail()) { ?>
		<div class="sc_courses_item_thumb">
			<?php the_post_thumbnail( alex_stone_get_thumb_size($args['columns'] > 2 ? 'courses' : 'big'), array('alt' => the_title_attribute( array( 'echo' => false ) )) ); ?>
			<div class="trx_addons_hover_mask"></div>
			<div class="sc_courses_item_info">
				<div class="sc_courses_item_header">
					<h4 class="sc_courses_item_title"><?php the_title(); ?></h4>
					<div class="sc_courses_item_meta">
						<div class="trx_addons_hover_meta">
							<span class="sc_courses_item_meta_item sc_courses_item_meta_duration"><?php echo esc_html($meta['duration']); ?></span>
							<?php
							if (!empty($meta['price'])) {
							?>
							<div class="sc_courses_item_meta_item"><?php
								$price = explode('/', $meta['price']);
								echo esc_html($price[0]) . (!empty($price[1]) ? '<span class="sc_courses_item_period">'.$price[1].'</span>' : '');
							?></div>
							<?php
							}
							?>
						</div>
		            </div>
				</div>
			</div>
			<div class="trx_addons_hover_content">
				<h4 class="trx_addons_hover_title"><?php the_title(); ?></h4>
				<div class="trx_addons_hover_links">
					<div class="trx_addons_hover_links">
						<a href="<?php echo esc_url(get_permalink()); ?>" class="trx_addons_hover_link"><?php esc_html_e('learn more', 'alex-stone'); ?></a>
						<?php if (!empty($meta['product']) && (int) $meta['product'] > 0) { ?>
						<a href="<?php echo esc_url(get_permalink($meta['product'])); ?>" class="trx_addons_hover_link"><?php esc_html_e('Buy Now', 'alex-stone'); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>			
		</div>
	<?php } ?>
</div>
<?php
if (!empty($args['slider']) || $args['columns'] > 1) {
	?></div><?php
}
?>