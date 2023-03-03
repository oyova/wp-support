<?php

namespace Oyova\WpSupport\Acf\Blocks;

class Block {
	public $block = null;

	public function __construct( array $block ) {
		$this->block = $block;
	}

	public function get_element_open(): string {
		$type = $this->get_element_type();

		return oyo_format_string(
			'<!type id=":id" class=":class" style=":style">',
			array(
				'!type'  => $type,
				':id'    => $this->get_element_id(),
				':class' => $this->get_element_class(),
				':style' => $this->get_element_style(),
			)
		);
	}

	public function get_element_close(): string {
		$type = $this->get_element_type();

		return oyo_format_string(
			'</!type>',
			array(
				'!type' => $type,
			)
		);
	}

	public function get_element_class(): string {
		return 'c-' . sanitize_title( $this->block['title'] );
	}

	public function get_element_style(): string {
		return implode( ';', $this->get_styles() );
	}

	public function get_element_id(): string {
		return $this->block['anchor'] ?? '';
	}

	public function get_element_type() {
		if ( ! isset( $this->block['element_type'] ) ) {
			return 'div';
		}

		if ( ! is_string( $this->block['element_type'] ) ) {
			return 'div';
		}

		if ( ! ctype_alpha( $this->block['element_type'] ) ) {
			return 'div';
		}

		return $this->block['element_type'];
	}

	public function get_styles(): array {
		return array_merge(
			$this->get_margin_styles(),
			$this->get_padding_styles(),
		);
	}

	public function get_margin_styles(): array {
		$styles = array();

		if ( ! isset( $this->block['style']['spacing']['margin'] ) ) {
			return $styles;
		}

		foreach ( $this->block['style']['spacing']['margin'] as $key => $margin ) {
			if ( str_contains( $margin, 'var:' ) ) {
				$margin   = str_replace( array( 'var:', '|' ), array( '', '--' ), $margin );
				$styles[] = sprintf( 'margin-%s:var(--wp--%s)', $key, $margin );
			} else {
				$styles[] = sprintf( 'margin-%s:%s', $key, $margin );
			}
		}

		return $styles;
	}

	public function get_padding_styles(): array {
		$styles = array();

		if ( ! isset( $this->block['style']['spacing']['padding'] ) ) {
			return $styles;
		}

		foreach ( $this->block['style']['spacing']['padding'] as $key => $padding ) {
			if ( str_contains( $padding, 'var:' ) ) {
				$padding  = str_replace( array( 'var:', '|' ), array( '', '--' ), $padding );
				$styles[] = sprintf( 'padding-%s:var(--wp--%s)', $key, $padding );
			} else {
				$styles[] = sprintf( 'padding-%s:%s', $key, $padding );
			}
		}

		return $styles;
	}
}
