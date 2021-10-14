<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.1
 */
 
$alex_stone_theme_obj = wp_get_theme();
?>
<div class="update-nag" id="alex_stone_admin_notice">
	<h3 class="alex_stone_notice_title"><?php echo sprintf(esc_html__('Welcome to %s v.%s', 'alex-stone'), $alex_stone_theme_obj->name.(ALEX_STONE_THEME_FREE ? ' '.esc_html__('Free', 'alex-stone') : ''), $alex_stone_theme_obj->version); ?></h3>
	<?php
	if (!alex_stone_exists_trx_addons()) {
		?><p><?php echo wp_kses_data(__('<b>Attention!</b> Plugin "ThemeREX Addons is required! Please, install and activate it!', 'alex-stone')); ?></p><?php
	}
	?><p>
		<a href="<?php echo esc_url(admin_url().'themes.php?page=alex_stone_about'); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> <?php echo sprintf(esc_html__('About %s', 'alex-stone'), $alex_stone_theme_obj->name); ?></a>
		<?php
		if (alex_stone_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'alex-stone'); ?></a>
			<?php
		}
		if (function_exists('alex_stone_exists_trx_addons') && alex_stone_exists_trx_addons() && class_exists('trx_addons_demo_data_importer')) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'alex-stone'); ?></a>
			<?php
		}
		?>
        <a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'alex-stone'); ?></a>
		<span> <?php esc_html_e('or', 'alex-stone'); ?> </span>
        <a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Options', 'alex-stone'); ?></a>
        <a href="#" class="button alex_stone_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'alex-stone'); ?></a>
	</p>
</div>