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

if ( ! function_exists( 'oyo_format_string' ) ) {
	/**
	 * Get a string formatted against an array of attribute/value pairs. Values
	 * are escaped according the first charachter of the attribute name.
	 *
	 * Attribute name parsing:
	 * @ - Uses esc_html()
	 * # - Uses esc_url()
	 * : - Uses esc_attr()
	 * ! - Unescaped: Only use content that has been sanitized already.
	 *
	 * @param string $string The string to format
	 * @param array  $attributes The array of attribute/value pairs
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
