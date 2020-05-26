<?php

namespace OptimizeWP;

class Admin implements ServiceInterface {

	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		add_action( 'admin_init', [ $this, 'admin_init_settings' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu_settings' ] );
	}

	public function call() {

	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function admin_init_settings() {

		$sections = [
			[
				'id'    => 'options',
				'title' => __( 'Options', 'optimizewp' ),
			],
			[
				'id'    => 'license',
				'title' => __( 'License', 'optimizewp' ),
			],
			[
				'id'    => 'addons',
				'title' => __( 'Addons', 'optimizewp' ),
			],
			[
				'id'    => 'support',
				'title' => __( 'Support', 'optimizewp' ),
			],
		];

		$fields = [
			'options' => [
				[
					'name'    => 'text',
					'label'   => __( 'Text Input', 'optimizewp' ),
					'desc'    => __( 'Text input description', 'optimizewp' ),
					'type'    => 'text',
					'default' => 'Title',
				],
				[
					'name'  => 'textarea',
					'label' => __( 'Textarea Input', 'optimizewp' ),
					'desc'  => __( 'Textarea description', 'optimizewp' ),
					'type'  => 'textarea',
				],
				[
					'name'  => 'checkbox',
					'label' => __( 'Checkbox', 'optimizewp' ),
					'desc'  => __( 'Checkbox Label', 'optimizewp' ),
					'type'  => 'checkbox',
				],
				[
					'name'    => 'radio',
					'label'   => __( 'Radio Button', 'optimizewp' ),
					'desc'    => __( 'A radio button', 'optimizewp' ),
					'type'    => 'radio',
					'options' => [
						'yes' => 'Yes',
						'no'  => 'No',
					],
				],
				[
					'name'    => 'multicheck',
					'label'   => __( 'Multile checkbox', 'optimizewp' ),
					'desc'    => __( 'Multi checkbox description', 'optimizewp' ),
					'type'    => 'multicheck',
					'options' => [
						'one'   => 'One',
						'two'   => 'Two',
						'three' => 'Three',
						'four'  => 'Four',
					],
				],
				[
					'name'    => 'selectbox',
					'label'   => __( 'A Dropdown', 'optimizewp' ),
					'desc'    => __( 'Dropdown description', 'optimizewp' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => [
						'yes' => 'Yes',
						'no'  => 'No',
					],
				],
				[
					'name'    => 'password',
					'label'   => __( 'Password', 'optimizewp' ),
					'desc'    => __( 'Password description', 'optimizewp' ),
					'type'    => 'password',
					'default' => '',
				],
				[
					'name'    => 'file',
					'label'   => __( 'File', 'optimizewp' ),
					'desc'    => __( 'File description', 'optimizewp' ),
					'type'    => 'file',
					'default' => '',
				],
				[
					'name'    => 'color',
					'label'   => __( 'Color', 'optimizewp' ),
					'desc'    => __( 'Color description', 'optimizewp' ),
					'type'    => 'color',
					'default' => '',
				],
			],
			'license' => [
				[
					'name'    => 'text',
					'label'   => __( 'Text Input', 'optimizewp' ),
					'desc'    => __( 'Text input description', 'optimizewp' ),
					'type'    => 'text',
					'default' => 'Title',
				],
			],
			'addons'  => [
				[
					'name'    => 'text',
					'label'   => __( 'Text Input', 'optimizewp' ),
					'desc'    => __( 'Text input description', 'optimizewp' ),
					'type'    => 'text',
					'default' => 'Title',
				],
			],
			'support' => [
				[
					'name'    => 'text',
					'label'   => __( 'Text Input', 'optimizewp' ),
					'desc'    => __( 'Text input description', 'optimizewp' ),
					'type'    => 'text',
					'default' => 'Title',
				],
			],
		];

		$settings_api = \WeDevs_Settings_API::getInstance();

		//set sections and fields
		$settings_api->set_sections( $sections );
		$settings_api->set_fields( $fields );

		//initialize them
		$settings_api->admin_init();
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function admin_menu_settings() {
		\add_options_page(
			'OptimizeWP',
			'OptimizeWP',
			'manage_options',
			'optimizewp',
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
	function settings_page() {
		$settings_api = \WeDevs_Settings_API::getInstance();

		echo '<div class="wrap">';
		echo '<h2>OptimizeWP Settings</h2>';

		$settings_api->show_navigation();
		$settings_api->show_forms();

		echo '</div>';
	}
}
