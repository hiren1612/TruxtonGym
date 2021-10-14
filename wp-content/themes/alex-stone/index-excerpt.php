<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

alex_stone_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	?><div class="posts_container"><?php
	
	$alex_stone_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$alex_stone_sticky_out = alex_stone_get_theme_option('sticky_style')=='columns' 
							&& is_array($alex_stone_stickies) && count($alex_stone_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($alex_stone_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($alex_stone_sticky_out && !is_sticky()) {
			$alex_stone_sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $alex_stone_sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($alex_stone_sticky_out) {
		$alex_stone_sticky_out = false;
		?></div><?php
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