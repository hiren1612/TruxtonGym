<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.14
 */
$alex_stone_header_video = alex_stone_get_header_video();
$alex_stone_embed_video = '';
if (!empty($alex_stone_header_video) && !alex_stone_is_from_uploads($alex_stone_header_video)) {
	if (alex_stone_is_youtube_url($alex_stone_header_video) && preg_match('/[=\/]([^=\/]*)$/', $alex_stone_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$alex_stone_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($alex_stone_header_video) . '[/embed]' ));
			$alex_stone_embed_video = alex_stone_make_video_autoplay($alex_stone_embed_video);
		} else {
			$alex_stone_header_video = str_replace('/watch?v=', '/embed/', $alex_stone_header_video);
			$alex_stone_header_video = alex_stone_add_to_url($alex_stone_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$alex_stone_embed_video = '<iframe src="' . esc_url($alex_stone_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php alex_stone_show_layout($alex_stone_embed_video); ?></div><?php
	}
}
?>