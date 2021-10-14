<?php
/**
 * Information about this theme
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.30
 */


// Redirect to the 'About Theme' page after switch theme
if (!function_exists('alex_stone_about_after_switch_theme')) {
	add_action('after_switch_theme', 'alex_stone_about_after_switch_theme', 1000);
	function alex_stone_about_after_switch_theme() {
		update_option('alex_stone_about_page', 1);
	}
}
if ( !function_exists('alex_stone_about_after_setup_theme') ) {
	add_action( 'init', 'alex_stone_about_after_setup_theme', 1000 );
	function alex_stone_about_after_setup_theme() {
		if (get_option('alex_stone_about_page') == 1) {
			update_option('alex_stone_about_page', 0);
			wp_safe_redirect(admin_url().'themes.php?page=alex_stone_about');
			exit();
		}
	}
}


// Add 'About Theme' item in the Appearance menu
if (!function_exists('alex_stone_about_add_menu_items')) {
	add_action( 'admin_menu', 'alex_stone_about_add_menu_items' );
	function alex_stone_about_add_menu_items() {  
		$theme = wp_get_theme();  
		$theme_name = $theme->name . (ALEX_STONE_THEME_FREE ? ' ' . esc_html__('Free', 'alex-stone') : '');  
		add_theme_page(   sprintf(esc_html__('About %s', 'alex-stone'), $theme_name), //page_title   
		sprintf(esc_html__('About %s', 'alex-stone'), $theme_name), //menu_title   
		'manage_options',           //capability   
		'alex_stone_about',           //menu_slug   
		'alex_stone_about_page_builder'       //callback
		); 
	}
}



// Load page-specific scripts and styles
if (!function_exists('alex_stone_about_enqueue_scripts')) {
	add_action( 'admin_enqueue_scripts', 'alex_stone_about_enqueue_scripts' );
	function alex_stone_about_enqueue_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_alex_stone_about') {
			// Scripts
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			if ( ($fdir = alex_stone_get_file_url('theme-specific/theme.about/theme.about.js')) != '' )
				wp_enqueue_script( 'alex-stone-about', $fdir, array('jquery'), null, true );
			
			if (function_exists('alex_stone_plugins_installer_enqueue_scripts'))
				alex_stone_plugins_installer_enqueue_scripts();
			
			// Styles
			wp_enqueue_style( 'fontello-icons',  alex_stone_get_file_url('css/font-icons/css/fontello-embedded.css') );
			if ( ($fdir = alex_stone_get_file_url('theme-specific/theme.about/theme.about.css')) != '' )
				wp_enqueue_style( 'alex-stone-about',  $fdir, array(), null );
		}
	}
}


// Build 'About Theme' page
if (!function_exists('alex_stone_about_page_builder')) {
	function alex_stone_about_page_builder() {
		$theme = wp_get_theme();
		?>
		<div class="alex_stone_about">
			<div class="alex_stone_about_header">
				<div class="alex_stone_about_logo"><?php
					$logo = alex_stone_get_file_url('theme-specific/theme.about/logo.jpg');
					if (empty($logo)) $logo = alex_stone_get_file_url('screenshot.jpg');
					if (!empty($logo)) {
						?><img src="<?php echo esc_url($logo); ?>"><?php
					}
				?></div>
				
				<?php if (ALEX_STONE_THEME_FREE) { ?>
					<a href="<?php echo esc_url(alex_stone_storage_get('theme_download_url')); ?>"
										   target="_blank"
										   class="alex_stone_about_pro_link button button-primary"><?php
											esc_html_e('Get PRO version', 'alex-stone');
										?></a>
				<?php } ?>
				<h1 class="alex_stone_about_title"><?php
					echo sprintf(esc_html__('Welcome to %s %s v.%s', 'alex-stone'),
								$theme->name,
								ALEX_STONE_THEME_FREE ? esc_html__('Free', 'alex-stone') : '',
								$theme->version
								);
				?></h1>
				<div class="alex_stone_about_description">
					<?php
					if (ALEX_STONE_THEME_FREE) {
						?><p><?php
							echo wp_kses_data(sprintf(__('Now you are using Free version of <a href="%s">%s Pro Theme</a>.', 'alex-stone'),
														esc_url(alex_stone_storage_get('theme_download_url')),
														$theme->name
														)
												);
							echo '<br>' . wp_kses_data(sprintf(__('This version is SEO- and Retina-ready. It also has a built-in support for parallax and slider with swipe gestures. %s Free is compatible with many popular plugins, such as %s', 'alex-stone'),
														$theme->name,
														alex_stone_about_get_supported_plugins()
														)
												);
						?></p>
						<p><?php
							echo wp_kses_data(sprintf(__('We hope you have a great acquaintance with our themes. If you are looking for a fully functional website, you can get the <a href="%s">Pro Version here</a>', 'alex-stone'),
														esc_url(alex_stone_storage_get('theme_download_url'))
														)
												);
						?></p><?php
					} else {
						?><p><?php
							echo wp_kses_data(sprintf(__('%s is a Premium WordPress theme. It has a built-in support for parallax, slider with swipe gestures, and is SEO- and Retina-ready', 'alex-stone'),
														$theme->name
														)
												);
						?></p>
						<p><?php
							echo wp_kses_data(sprintf(__('The Premium Theme is compatible with many popular plugins, such as %s', 'alex-stone'),
														alex_stone_about_get_supported_plugins()
														)
												);
						?></p><?php
					}
					?>
				</div>
			</div>
			<div id="alex_stone_about_tabs" class="alex_stone_tabs alex_stone_about_tabs">
				<ul>
					<li><a href="#alex_stone_about_section_start"><?php esc_html_e('Getting started', 'alex-stone'); ?></a></li>
					<li><a href="#alex_stone_about_section_actions"><?php esc_html_e('Recommended actions', 'alex-stone'); ?></a></li>
					<?php if (ALEX_STONE_THEME_FREE) { ?>
						<li><a href="#alex_stone_about_section_pro"><?php esc_html_e('Free vs PRO', 'alex-stone'); ?></a></li>
					<?php } ?>
				</ul>
				<div id="alex_stone_about_section_start" class="alex_stone_tabs_section alex_stone_about_section"><?php
				
					// Install required plugins
					if (!alex_stone_exists_trx_addons()) {
						?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
							<h2 class="alex_stone_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'alex-stone'); ?>
							</h2>
							<div class="alex_stone_about_block_description"><?php
								echo esc_html(sprintf(esc_html__('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'alex-stone'), $theme->name));
							?></div>
							<?php alex_stone_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'alex-stone'); ?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							echo esc_html(sprintf(esc_html__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'alex-stone'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="alex_stone_about_block_link button button-primary"><?php
							esc_html_e('Install plugins', 'alex-stone');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'alex-stone'); ?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'alex-stone');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   class="alex_stone_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'alex-stone');
						?></a>
						<?php esc_html_e('or', 'alex-stone'); ?>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
						   class="alex_stone_about_block_link button"><?php
							esc_html_e('Theme Options', 'alex-stone');
						?></a>
					</div></div><?php
					
					// Documentation
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-book"></i>
							<?php esc_html_e('Read full documentation', 'alex-stone');	?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							echo esc_html(sprintf(esc_html__('Need more details? Please check our full online documentation for detailed information on how to use %s.', 'alex-stone'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(alex_stone_storage_get('theme_doc_url')); ?>"
						   target="_blank"
						   class="alex_stone_about_block_link button button-primary"><?php
							esc_html_e('Documentation', 'alex-stone');
						?></a>
					</div></div><?php
					
					// Support
					if (!ALEX_STONE_THEME_FREE) {
						?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
							<h2 class="alex_stone_about_block_title">
								<i class="dashicons dashicons-sos"></i>
								<?php esc_html_e('Support', 'alex-stone'); ?>
							</h2>
							<div class="alex_stone_about_block_description"><?php
								echo esc_html(sprintf(esc_html__('We want to make sure you have the best experience using %s and that is why we gathered here all the necessary informations for you.', 'alex-stone'), $theme->name));
							?></div>
							<a href="<?php echo esc_url(alex_stone_storage_get('theme_support_url')); ?>"
							   target="_blank"
							   class="alex_stone_about_block_link button button-primary"><?php
								esc_html_e('Support', 'alex-stone');
							?></a>
						</div></div><?php
					}
					
					// Online Demo
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-images-alt2"></i>
							<?php esc_html_e('On-line demo', 'alex-stone'); ?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							echo esc_html(sprintf(esc_html__('Visit the Demo Version of %s to check out all the features it has', 'alex-stone'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(alex_stone_storage_get('theme_demo_url')); ?>"
						   target="_blank"
						   class="alex_stone_about_block_link button button-primary"><?php
							esc_html_e('View demo', 'alex-stone');
						?></a>
					</div></div>
					
				</div>



				<div id="alex_stone_about_section_actions" class="alex_stone_tabs_section alex_stone_about_section"><?php
				
					// Install required plugins
					if (!alex_stone_exists_trx_addons()) {
						?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
							<h2 class="alex_stone_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'alex-stone'); ?>
							</h2>
							<div class="alex_stone_about_block_description"><?php
								echo esc_html(sprintf(esc_html__('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'alex-stone'), $theme->name));
							?></div>
							<?php alex_stone_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'alex-stone'); ?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							echo esc_html(sprintf(esc_html__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'alex-stone'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="alex_stone_about_block_link button button button-primary"><?php
							esc_html_e('Install plugins', 'alex-stone');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="alex_stone_about_block"><div class="alex_stone_about_block_inner">
						<h2 class="alex_stone_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'alex-stone'); ?>
						</h2>
						<div class="alex_stone_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'alex-stone');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   target="_blank"
						   class="alex_stone_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'alex-stone');
						?></a>
						<?php esc_html_e('or', 'alex-stone'); ?>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
						   class="alex_stone_about_block_link button"><?php
							esc_html_e('Theme Options', 'alex-stone');
						?></a>
					</div></div>
					
				</div>



				<?php if (ALEX_STONE_THEME_FREE) { ?>
					<div id="alex_stone_about_section_pro" class="alex_stone_tabs_section alex_stone_about_section">
						<table class="alex_stone_about_table" cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>
									<td class="alex_stone_about_table_info">&nbsp;</td>
									<td class="alex_stone_about_table_check"><?php echo esc_html(sprintf(esc_html__('%s Free', 'alex-stone'), $theme->name)); ?></td>
									<td class="alex_stone_about_table_check"><?php echo esc_html(sprintf(esc_html__('%s PRO', 'alex-stone'), $theme->name)); ?></td>
								</tr>
							</thead>
							<tbody>
	
	
								<?php
								// Responsive layouts
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Mobile friendly', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Responsive layout. Looks great on any device.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Built-in slider
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Built-in posts slider', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Allows you to add beautiful slides using the built-in shortcode/widget "Slider" with swipe gestures support.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Revolution slider
								if (alex_stone_storage_isset('required_plugins', 'revslider')) {
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Revolution Slider Compatibility', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Our built-in shortcode/widget "Slider" is able to work not only with posts, but also with slides created  in "Revolution Slider".', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// SiteOrigin Panels
								if (alex_stone_storage_isset('required_plugins', 'siteorigin-panels')) {
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Free PageBuilder', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Full integration with a nice free page builder "SiteOrigin Panels".', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Additional widgets pack', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('A number of useful widgets to create beautiful homepages and other sections of your website with SiteOrigin Panels.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// WPBakery Page Builder
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('WPBakery Page Builder', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Full integration with a very popular page builder "WPBakery Page Builder". A number of useful shortcodes and widgets to create beautiful homepages and other sections of your website.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Additional shortcodes pack', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('A number of useful shortcodes to create beautiful homepages and other sections of your website with WPBakery Page Builder.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Layouts builder
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Headers and Footers builder', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Powerful visual builder of headers and footers! No manual code editing - use all the advantages of drag-and-drop technology.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// WooCommerce
								if (alex_stone_storage_isset('required_plugins', 'woocommerce')) {
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('WooCommerce Compatibility', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Ready for e-commerce. You can build an online store with this theme.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// Easy Digital Downloads
								if (alex_stone_storage_isset('required_plugins', 'easy-digital-downloads')) {
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Easy Digital Downloads Compatibility', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Ready for digital e-commerce. You can build an online digital store with this theme.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// Other plugins
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Many other popular plugins compatibility', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('PRO version is compatible (was tested and has built-in support) with many popular plugins.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Support
								?>
								<tr>
									<td class="alex_stone_about_table_info">
										<h2 class="alex_stone_about_table_info_title">
											<?php esc_html_e('Support', 'alex-stone'); ?>
										</h2>
										<div class="alex_stone_about_table_info_description"><?php
											esc_html_e('Our premium support is going to take care of any problems, in case there will be any of course.', 'alex-stone');
										?></div>
									</td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="alex_stone_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Get PRO version
								?>
								<tr>
									<td class="alex_stone_about_table_info">&nbsp;</td>
									<td class="alex_stone_about_table_check" colspan="2">
										<a href="<?php echo esc_url(alex_stone_storage_get('theme_download_url')); ?>"
										   target="_blank"
										   class="alex_stone_about_block_link alex_stone_about_pro_link button button-primary"><?php
											esc_html_e('Get PRO version', 'alex-stone');
										?></a>
									</td>
								</tr>
	
							</tbody>
						</table>
					</div>
				<?php } ?>
				
			</div>
		</div>
		<?php
	}
}


// Utils
//------------------------------------

// Return supported plugin's names
if (!function_exists('alex_stone_about_get_supported_plugins')) {
	function alex_stone_about_get_supported_plugins() {
		return '"' . join('", "', array_values(alex_stone_storage_get('required_plugins'))) . '"';
	}
}

require_once ALEX_STONE_THEME_DIR . 'includes/plugins.installer/plugins.installer.php';
?>