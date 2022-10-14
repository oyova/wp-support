<?php

if ( ! function_exists( 'oyo_blank' ) ) {
	/**
	 * Determine if the given value is "blank." E.g. empty string or array
	 */
	function oyo_blank( $val ): bool {
		if ( is_null( $val ) ) {
			return true;
		}

		if ( is_string( $val ) ) {
			return trim( $val ) === '';
		}

		if ( is_numeric( $val ) || is_bool( $val ) ) {
			return false;
		}

		if ( $val instanceof Countable ) {
			return count( $val ) === 0;
		}

		return empty( $val );
	}
}

if ( ! function_exists( 'oyo_element_attr' ) ) {
	/**
	 * Get an attribute's value for an HTML element.
	 *
	 * Usage: oyo_element_attr('<a href="example.com">', 'href') yields example.com
	 */
	function oyo_element_attr( string $html_el, string $attr ): ?string {
		if ( oyo_blank( $attr ) ) {
			throw new Exception( 'A valid attr must be provided.' );
		}

		preg_match( "/{$attr}=\"(.+?)\"/", $html_el, $matches );

		if ( ! isset( $matches[1] ) || oyo_blank( $matches[1] ) ) {
			return null;
		}

		return $matches[1];
	}
}
