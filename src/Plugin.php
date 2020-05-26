<?php

namespace OptimizeWP;

class Plugin implements ServiceInterface {
	public $file;
	public $base;
	public $slug;
	public $dir;
	public $url;
	public $name;
	public $data;
	public $version;
	public $author;
	public $author_uri;
	public $plugin_uri;
	public $text_domain;
	public $domain_path;

	public function __construct( $file ) {
		$data = \get_file_data( $file, [
			'Name'        => 'Plugin Name',
			'Plugin URI'  => 'Plugin URI',
			'Version'     => 'Version',
			'Description' => 'Description',
			'Author'      => 'Author',
			'Author URI'  => 'Author URI',
			'Text Domain' => 'Text Domain',
			'Domain Path' => 'Domain Path',
			'Network'     => 'Network',
		] );

		$defaults = [
			'file'        => $file,
			'base'        => \plugin_basename( $file ),
			'slug'        => \basename( $file, '.php' ),
			'dir'         => \trailingslashit( \dirname( $file ) ),
			'url'         => \trailingslashit( \plugin_dir_url( $file ) ),
			'name'        => $data['Name'],
			'version'     => $data['Version'],
			'author'      => $data['Author'],
			'author_uri'  => $data['Author URI'],
			'plugin_uri'  => $data['Plugin URI'],
			'text_domain' => $data['Text Domain'],
			'domain_path' => $data['Domain Path'],
		];

		foreach ( $defaults as $property => $value ) {
			$this->{$property} = $value;
		}
	}

	public function call() {
		\register_activation_hook( $this->file, function () {
			$this->activate();
		} );

		\register_deactivation_hook( $this->file, function () {
			$this->deactivate();
		} );
	}

	public function add_textdomain() {
		\load_plugin_textdomain( $this->text_domain, 0, $this->domain_path );
	}

	public function activate() {
		\flush_rewrite_rules();
	}

	public function deactivate() {
		\flush_rewrite_rules();
	}
}
