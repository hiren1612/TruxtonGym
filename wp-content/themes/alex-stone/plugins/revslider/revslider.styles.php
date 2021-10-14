<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'alex_stone_revslider_get_css' ) ) {
	add_filter( 'alex_stone_filter_get_css', 'alex_stone_revslider_get_css', 10, 4 );
	function alex_stone_revslider_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

.custom .tp-bullet {
	border-color: transparent;
	background-color: transparent;
}

.custom .tp-bullet:before {
	background-color: {$colors['inverse_link']};
}

.custom .tp-bullet:hover, 
.custom .tp-bullet.selected {
	border-color: {$colors['text_link']};
	background-color: transparent;
}
.custom .tp-bullet.selected:hover::before, 
.custom .tp-bullet:hover::before,
.custom .tp-bullet.selected:before {
	background-color: {$colors['text_link']};
}



CSS;
		}
		
		return $css;
	}
}
?>