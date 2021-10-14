<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'alex_stone_vc_get_css' ) ) {
	add_filter( 'alex_stone_filter_get_css', 'alex_stone_vc_get_css', 10, 4 );
	function alex_stone_vc_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
.vc_message_box > p,
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units,
.vc_progress_bar .vc_single_bar .vc_label,
.vc_tta.vc_tta-tabs.vc_tta-style-classic.vc_tta-shape-square .vc_tta-tab > a {
	{$fonts['h5_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Row and columns */
.scheme_self.vc_section,
.scheme_self.wpb_row,
.scheme_self.wpb_column > .vc_column-inner > .wpb_wrapper,
.scheme_self.wpb_text_column {
	color: {$colors['text']};
}
.scheme_self.vc_section[data-vc-full-width="true"],
.scheme_self.wpb_row[data-vc-full-width="true"],
.scheme_self.wpb_column > .vc_column-inner > .wpb_wrapper,
.scheme_self.wpb_text_column {
	background-color: {$colors['bg_color']};
}
.scheme_self.vc_row.vc_parallax[class*="scheme_"] .vc_parallax-inner:before {
	background-color: {$colors['bg_color_08']};
}

/* Accordion */
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon.vc_tta-controls-icon-chevron {
	color: {$colors['inverse_link']};
	background-color: transparent;
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a {
	color: {$colors['text_dark']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover {
	color: {$colors['text_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon.vc_tta-controls-icon-chevron,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .vc_tta-controls-icon.vc_tta-controls-icon-chevron {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}

.vc_tta-accordion.vc_tta-style-classic.vc_tta-shape-square .vc_tta-panel {
	border-color: {$colors['text_dark_065']};
}

.vc_tta-accordion.vc_tta-style-classic.vc_tta-shape-square .vc_tta-panel:not(.vc_active){
	border-top-color: transparent;
	border-left-color: transparent;
	border-right-color: transparent;
}
.vc_tta-accordion.vc_tta-style-classic.vc_tta-shape-square .vc_tta-panel:not(.vc_active):last-child{
	border-bottom-color: transparent;
}

.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-panel-title > a > .vc_tta-controls-icon.vc_tta-controls-icon-chevron {
	color: {$colors['text_dark']};
}

.wpb-js-composer .vc_tta-color-white.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a {
	color: {$colors['alter_dark']};
}

/* Tabs */
.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels .vc_tta-panel:not(.vc_active) .vc_tta-panel-heading,
.vc_tta-style-classic.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab > a {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}
.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels .vc_tta-panel:not(.vc_active) .vc_tta-panel-heading a{
	color: {$colors['inverse_link']};
}

.vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab > a:hover,
.vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-tabs-list .vc_tta-tab.vc_active > a {
	color: {$colors['text_dark']};
	background-color: {$colors['alter_bg_color']};
}
.vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels-container .vc_tta-panels {
	background-color: {$colors['alter_bg_color']};
}

/* Separator */
.vc_separator.vc_sep_color_grey .vc_sep_line {
	border-color: {$colors['bd_color']};
}

/* Progress bar */
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar {
	background-color: {$colors['alter_bg_color']};
}
.vc_progress_bar.vc_progress_bar_narrow.vc_progress-bar-color-bar_red .vc_single_bar .vc_bar {
	background-color: {$colors['alter_link']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label {
	color: {$colors['alter_dark']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	color: {$colors['alter_dark']};
}

CSS;
		}
		
		return $css;
	}
}
?>