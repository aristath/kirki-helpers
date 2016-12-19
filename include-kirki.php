<?php
/**
 * This file implements custom requirements for the Kirki plugin.
 * It can be used as-is in themes (drop-in).
 *
 * @package kirki-helpers
 */

if ( ! class_exists( 'Kirki' ) ) {

	if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'Kirki_Installer_Section' ) ) {
		/**
		 * Recommend the installation of Kirki using a custom section.
		 *
		 * @see WP_Customize_Section
		 */
		class Kirki_Installer_Section extends WP_Customize_Section {

			/**
			 * Customize section type.
			 *
			 * @access public
			 * @var string
			 */
			public $type = 'kirki_installer';

			/**
			 * Render the section.
			 *
			 * @access protected
			 */
			protected function render() {
				// Determine if the plugin is not installed, or just inactive.
				$plugins   = get_plugins();
				$installed = false;
				foreach ( $plugins as $plugin ) {
					if ( 'Kirki' === $plugin['Name'] || 'Kirki Toolkit' === $plugin['Name'] ) {
						$installed = true;
					}
				}
				// Get the plugin-installation URL.
				$plugin_install_url = add_query_arg(
					array(
						'action' => 'install-plugin',
						'plugin' => 'kirki',
					),
					self_admin_url( 'update.php' )
				);
				$plugin_install_url = wp_nonce_url( $plugin_install_url, 'install-plugin_kirki' );
				?>
				<div style="padding:10px 14px;">
					<?php if ( ! $installed ) : ?>
						<?php esc_attr_e( 'A plugin is required to take advantage of this theme\'s features in the customizer.', 'textdomain' ); ?>
						<a class="install-now button-primary button" data-slug="kirki" href="<?php echo esc_url_raw( $plugin_install_url ); ?>" aria-label="Install Kirki Toolkit now" data-name="Kirki Toolkit"><?php esc_html_e( 'Install Now', 'textdomain' ); ?></a>
					<?php else : ?>
						<?php esc_attr_e( 'A plugin is required to take advantage of this theme\'s features in the customizer.', 'textdomain' ); ?>
						<a class="install-now button-secondary button" data-slug="kirki" href="<?php echo esc_url_raw( self_admin_url( 'plugins.php' ) ); ?>" aria-label="Activate Kirki Toolkit now" data-name="Kirki Toolkit"><?php esc_html_e( 'Activate Now', 'textdomain' ); ?></a>
					<?php endif; ?>
				</div>
				<?php
			}
		}
	}

	if ( ! function_exists( 'kirki_installer_register' ) ) {
		/**
		 * Registers the section, setting & control for the kirki installer.
		 *
		 * @param object $wp_customize The main customizer object.
		 */
		function kirki_installer_register( $wp_customize ) {
			$wp_customize->add_section( new Kirki_Installer_Section( $wp_customize, 'kirki_installer', array(
				'title'      => '',
				'capability' => 'install_plugins',
				'priority'   => 0,
			) ) );
			$wp_customize->add_setting( 'kirki_installer_setting', array() );
			$wp_customize->add_control( 'kirki_installer_control', array(
				'section'    => 'kirki_installer',
				'settings'   => 'kirki_installer_setting',
			) );
		}
		add_action( 'customize_register', 'kirki_installer_register' );
	}
}
