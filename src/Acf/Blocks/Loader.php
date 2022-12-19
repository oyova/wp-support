<?php

namespace Oyova\WpSupport\Acf\Blocks;

class Loader {
	
	protected static $instance = null;
	private $acf_save_point = null;

	function __construct() {
		add_action( 'init', [ $this, 'register_blocks' ], 5 );
		add_filter( 'acf/settings/load_json', [ $this, 'acf_json_load_point' ] );
		add_action( 'acf/update_field_group', [ $this, 'acf_update_field_group' ], 1, 1 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_theme_styles' ] );
	}

	public static function instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function enqueue_theme_styles() {
		$style      = 'dist/css/style.css';
		$style_path = get_theme_file_path( $style );
		$style_uri  = get_theme_file_uri( $style );

		if( file_exists( $style_path ) ) {
			wp_enqueue_style( 'theme-styles', $style_uri, [], filemtime( $style_path ) );
		}
	}

	public function acf_update_field_group( $field_group ) {
		$blocks         = $this->get_blocks();
		$location_param = $field_group[ 'location' ][0][0][ 'param' ];
		$location_value = $field_group[ 'location' ][0][0][ 'value' ];

		if( 'block' !== $location_param ) {
			return;
		}

		foreach( $blocks as $block_name => $block ) {
			if( 'acf/' . $block_name !== $location_value ) {
				continue;
			}

			$this->acf_save_point = $block->directory;
			add_action( 'acf/settings/save_json',  [ $this, 'acf_json_save_point' ], 9999 );
			break;
		}

		return $field_group;
	}

	public function acf_json_save_point( $path ) {
		if( is_null( $this->acf_save_point ) ) {
			return $path;
		}

		return $this->acf_save_point;
	}

	public function acf_json_load_point( $paths ) {
		$blocks = $this->get_blocks();
	
		foreach( $blocks as $block_name => $block ) {
			$paths[] = $block->directory;
		}

		return $paths;
	}

	public function register_blocks() {
		$blocks = $this->get_blocks();

		foreach( $blocks as $block ) {
			if( isset( $block->init_file ) ) {
				require( $block->init_file );
			}

			register_block_type( $block->json_file );
		}
	}

	public function get_blocks() {
		$block_dirs = $this->get_block_dirs();
		$blocks     = [];

		foreach( $block_dirs as $block_name => $block_dir ) {
			$block = (object) [
				'directory' => $block_dir,
				'json_file' => path_join( $block_dir, 'block.json' ),
			];

			if( file_exists( path_join( $block_dir, 'functions.php' ) ) ) {
				$block->init_file = path_join( $block_dir, 'functions.php' );
			}

			$blocks[ $block_name ] = $block;
		}

		return apply_filters( 'oyo_acf_blocks', $blocks );
	}

	public function get_block_dirs( $cache = true ) {
		$block_dirs  = [];
		$search_dirs = array_unique( [
			path_join( get_stylesheet_directory(), 'blocks' ),
			path_join( get_template_directory(), 'blocks'),
		] );

		foreach( $search_dirs as $search_dir ) {
			if( $handle = opendir( $search_dir ) ) {
				while( false !== ( $entry = readdir( $handle ) ) ) {
					if( $entry === '.' || $entry === '..' ) {
						continue;
					}

					if( isset( $block_dirs[$entry] ) ) {
						continue;
					}

					$maybe_block_dir = path_join( $search_dir, $entry );

					if( file_exists( path_join( $maybe_block_dir, 'block.json' ) ) ) {
						$block_dirs[$entry] = $maybe_block_dir;
					}
				}

				closedir( $handle );
			}
		}

		$block_dirs = apply_filters( 'oyo_acf_block_dirs', $block_dirs );

		return $block_dirs;
	}
}
