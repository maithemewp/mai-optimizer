<?php

namespace MaiOptimizer;

class Admin {
	private $plugin;
	private $modules;

	public function __construct( Plugin $plugin, Modules $modules ) {
		$this->plugin  = $plugin;
		$this->modules = $modules;
	}

	public function add_hooks() {
		\add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		\add_action( 'admin_menu', [ $this, 'admin_menu_settings' ] );
		\add_action( 'admin_post_mai_optimizer', [ $this, 'form_submit' ] );
		\add_action( 'admin_bar_menu', [ $this, 'add_toolbar_items' ], 999 );
		\add_action( 'admin_post_mai_optimizer_toolbar', [ $this, 'handle_toolbar' ] );

	}

	public function enqueue_scripts() {
		\wp_enqueue_script(
			$this->plugin->slug,
			$this->plugin->url . 'resources/js/admin.js',
			[ 'jquery' ],
			$this->plugin->version,
			true
		);
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_menu_settings() {
		\add_submenu_page(
			'mai-theme',
			'Mai Optimizer',
			'Optimizations',
			'manage_options',
			'mai-optimizer',
			[ $this, 'settings_page' ]
		);
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function settings_page() {
		$options     = \get_option( $this->plugin->slug, [] );
		$default_tab = null;
		$tab         = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;
		?>
		<div class="wrap">
		<h1><?php echo $this->plugin->name; ?></h1>
		<br>
		<nav class="nav-tab-wrapper">
			<a href="?page=mai-optimizer" class="nav-tab <?php if ( $tab === null ) : ?>nav-tab-active<?php endif; ?> ">General</a>
			<a href=" ?page=mai-optimizer&tab=settings" class="nav-tab <?php if ( $tab === 'settings' ): ?>nav-tab-active<?php endif; ?>">WooCommerce</a>
			<a href=" ?page=mai-optimizer&tab=tools" class="nav-tab <?php if ( $tab === 'tools' ): ?>nav-tab-active<?php endif; ?>">Tools</a>
		</nav>
		<br>
		<div class="tab-content">
			<?php switch ( $tab ) :
				case 'settings':
					echo 'Settings';
					break;
				case 'tools':
					echo 'Tools';
					break;
				default:
					?>
					<p>
						<a href=" javascript:void(0)" class="mai-optimizer-toggle button button-secondary button-small">
							<?php esc_attr_e( 'Toggle All', 'mai-optimizer' ) ?>
						</a>
					</p>
					<form action="<?php esc_attr_e( admin_url( 'admin-post.php' ) ); ?>" method="post" class="mai-optimizer-form">
						<?php foreach ( $this->modules->get_module_names() as $module ) : ?>
							<?php $id = $module; ?>
							<?php $name = Helpers::convert_case( $module, 'title' ); ?>
							<?php $checked = isset( $options[ $module ] ) && \sanitize_text_field( $options[ $module ] ); ?>
							<label for="<?php esc_attr_e( $id ); ?>">
								<input type="checkbox" id="<?php esc_attr_e( $id ); ?>" name="<?php esc_attr_e( $this->plugin->slug ); ?>[<?php esc_attr_e( $id ); ?>]" <?php echo $checked ? 'checked' : ''; ?>>
								<?php esc_html_e( $name ); ?>
							</label>
							<br>
						<?php endforeach; ?>
						<br>
						<input type="hidden" name="action" value="mai_optimizer">
						<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e( 'Save Changes', 'mai-optimizer' ); ?>">
					</form>
					<?php
					break;
			endswitch; ?>
			</nav>
		</div>
		<?php
	}

	public function form_submit() {
		$settings = isset( $_REQUEST[ $this->plugin->slug ] ) ? $_REQUEST[ $this->plugin->slug ] : [];
		$modules  = $this->modules->get_module_names();

		foreach ( $modules as $module ) {
			if ( isset( $settings[ $module ] ) && 'on' === \sanitize_text_field( $settings[ $module ] ) ) {
				$modules[ $module ] = true;
			} else {
				$modules[ $module ] = false;
			}
		}

		\update_option( $this->plugin->slug, $modules );

		$this->clear_cache( 'admin.php?page=mai-optimizer' );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Admin_Bar $admin_bar
	 *
	 * @return void
	 */
	public function add_toolbar_items( $admin_bar ) {
		$current = \esc_url( $_SERVER['REQUEST_URI'] );

		$admin_bar->add_menu( [
			'id'    => $this->plugin->slug,
			'title' => $this->plugin->name,
			'href'  => \admin_url( 'admin.php?page=mai-optimizer' ),
			'meta'  => [
				'title' => __( $this->plugin->name ),
			],
		] );

		$admin_bar->add_menu( [
			'id'     => $this->plugin->slug . '-clear-cache',
			'parent' => $this->plugin->slug,
			'title'  => __( 'Clear Cache', 'mai-optimizer' ),
			'href'   => admin_url( 'admin-post.php?action=mai_optimizer_toolbar&current=' . $current ),
			'meta'   => [
				'title' => __( 'Clear Cache', 'mai-optimizer' ),
				'class' => $this->plugin->slug . '-clear-cache',
			],
		] );
	}

	public function handle_toolbar() {
		$redirect_url = isset( $_REQUEST['current'] ) ? str_replace( '/wp-admin/', '', $_REQUEST['current'] ) : '';

		$this->clear_cache( $redirect_url );
	}

	private function clear_cache( $redirect_url = '' ) {
		\update_option( $this->plugin->slug . '-cache', false );
		\wp_safe_redirect( \admin_url( $redirect_url ) );
	}
}
