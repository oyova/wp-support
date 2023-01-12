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
			return '' === trim( $val );
		}

		if ( is_numeric( $val ) || is_bool( $val ) ) {
			return false;
		}

		if ( $val instanceof Countable ) {
			return 0 === count( $val );
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

if ( ! function_exists( 'oyo_format_string' ) ) {
	/**
	 * Format a string against an associative array of key/value pairs. Anywhere
	 * in $string that a key from $attributes appears the corresponding value will
	 * be insterted and escaped.
	 *
	 * @param string $string The string to format
	 * @param array  $attributes The array of key/value pairs.
	 *
	 * Key prefix and value escape functions:
	 * "@" will use esc_html().
	 * "#" will use esc_url().
	 * ":" will use esc_attr().
	 * "!" will be unescaped and should only be used when the value has already
	 * been sanitized for output.
	 *
	 * @return string The formatted string
	 *
	 * Example Usage:
	 * echo oyo_format_string( '<a href="#url" title=":title">@title</a>', [
	 *     "#url" => "https://www.oyova.com",
	 *     ":title" => "Oyova",
	 *     "@title" => "Oyova",
	 * ] );
	 */
	function oyo_format_string( string $string, array $attributes = array() ): string {
		// transform arguments before inserting them.
		foreach ( $attributes as $key => $value ) {
			switch ( $key[0] ) {
				case '!':
					// leave value un-escaped
					break;

				case '#':
					// url escape value
					$attributes[ $key ] = esc_url( $value );
					break;

				case ':':
					// attr escape value
					$attributes[ $key ] = esc_attr( $value );
					break;

				default:
					// html escape value
					$attributes[ $key ] = esc_html( $value );
					break;
			}
		}

		return strtr( $string, $attributes );
	}
}
