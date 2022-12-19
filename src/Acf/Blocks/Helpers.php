<?php

namespace Oyova\WpSupport\Acf\Blocks;

class Helpers {
	
	/**
	 * 
	 */
	public static function get_block_classes ( 
		array $block,
		array $additional_classes = [] 
	): string {
		$default_classes = self::get_block_default_classes( $block );
		$margin_classes  = self::get_block_margin_classes( $block );
		$classes         = array_merge( 
			$default_classes,
			$margin_classes,
			$additional_classes,
		);

		$classes = apply_filters ( 'oyo_acf_block_classes', $classes, $block );

		return implode( ' ', $classes );
	}

	/**
	 * 
	 */
	public static function get_block_default_classes ( array $block ): array {
		$block_class        = 'c-block-' . sanitize_title( $block['title'] );
		$additional_classes = array();

		if ( isset( $block['data']['additional_classes'] ) ) {
			$additional_classes = array_filter (
				explode( ' ', trim( $block['data']['additional_classes'] ) )
			);
		}

		return array( $block_class, ...$additional_classes );
	}

	/**
	 * 
	 */
	public static function get_block_margin_classes ( array $block ): array {
		return array(); // WIP
	}

}
