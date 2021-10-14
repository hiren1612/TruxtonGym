<?php
/**
 * The template for homepage posts with "Chess" style
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

alex_stone_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$alex_stone_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$alex_stone_sticky_out = alex_stone_get_theme_option('sticky_style')=='columns' 
							&& is_array($alex_stone_stickies) && count($alex_stone_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($alex_stone_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$alex_stone_sticky_out) {
		?><div class="chess_wrap posts_container"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($alex_stone_sticky_out && !is_sticky()) {
			$alex_stone_sticky_out = false;
			?></div><div class="chess_wrap posts_container"><?php
		}
		get_template_part( 'content', $alex_stone_sticky_out && is_sticky() ? 'sticky' :'chess' );
	}
	
	?></div><?php

	alex_stone_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>