<?php

if ( ! function_exists( 'oyo_blank' ) ) {
	/**
	 * Determine if the given value is "blank".
	 * Credit to Laravel for this function.
	 */
	function oyo_blank( mixed $value ): bool {
		if ( is_null( $value ) ) {
			return true;
		}

		if ( is_string( $value ) ) {
			return '' === trim( $value );
		}

		if ( is_numeric( $value ) || is_bool( $value ) ) {
			return false;
		}

		if ( $value instanceof Countable ) {
			return 0 === count( $value );
		}

		return empty( $value );
	}
}

if ( ! function_exists( 'oyo_filled' ) ) {
	/**
	 * Determine if a value is "filled".
	 * Credit to Laravel for this function.
	 */
	function oyo_filled( mixed $value ): bool {
		return ! oyo_blank( $value );
	}
}

if ( ! function_exists( 'oyo_throw_if' ) ) {
	/**
	 * Throw the given exception if the given condition is true.
	 * Credit to Laravel for this function.
	 *
	 * @param string|\Throwable $exception
	 *
	 * @throws \Throwable
	 */
	function oyo_throw_if(
		mixed $condition,
		string|Exception $exception = 'RuntimeException',
		mixed ...$parameters
	) {
		if ( $condition ) {
			if ( is_string( $exception ) && class_exists( $exception ) ) {
				$exception = new $exception( ...$parameters );
			}

			throw is_string( $exception )
				? new RuntimeException( $exception )
				: $exception;
		}

		return $condition;
	}
}

if ( ! function_exists( 'oyo_throw_unless' ) ) {
	/**
	 * Throw the given exception unless the given condition is true.
	 * Credit to Laravel for this function.
	 *
	 * @param string|\Throwable $exception
	 *
	 * @throws \Throwable
	 */
	function oyo_throw_unless(
		mixed $condition,
		string|Exception $exception = 'RuntimeException',
		mixed ...$parameters
	) {
		oyo_throw_if( ! $condition, $exception, ...$parameters );

		return $condition;
	}
}

if ( ! function_exists( 'oyo_element_attr' ) ) {
	/**
	 * Get an attribute's value for an HTML element.
	 *
	 * Usage: oyo_element_attr('<a href="example.com">', 'href') yields
	 * example.com
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
	 * in $string that a key from $attributes appears the corresponding value
	 * will be inserted and escaped.
	 *
	 * @param string $string     The string to format
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
	function oyo_format_string(
		string $string,
		array $attributes = array()
	): string {
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

if ( ! function_exists( 'oyo_nl2_separator' ) ) {
	/**
	 * Insert a string at the end of each line in a string or at both the
	 * beggining and end of each line in a string.
	 *
	 * @param string $string The input string
	 * @param string $separator_1 The ending separator or beggining separator when $separator_2 is not empty
	 * @param string $separator_2 The ending separator
	 * @param bool   $trim_lines Trim whitespace from each line or not
	 *
	 * @return string The updated string
	 */
	function oyo_nl2_separator( string $string, string $separator_1, string $separator_2 = '', bool $trim_lines = false ): string {
		$lines = preg_split( "/\r\n|\n|\r/", $string );

		if ( $trim_lines ) {
			$lines = array_map( function ( $line ) {
				return trim( $line );
			}, $lines );
		}

		if ( empty( $separator_1 ) ) {
			return $string;
		}

		if ( empty( $separator_2 ) ) {
			return implode( $separator_1, $lines );
		}

		$lines = array_map( function ( $line ) use ( $separator_1, $separator_2 ) {
			return $separator_1 . $line . $separator_2;
		}, $lines );

		return implode( '', $lines );
	}
}

if ( ! function_exists( 'oyo_get_link' ) ) {
	/**
	 * Return a fromatted HTML <a> element.
	 *
	 * @param mixed $link_data
	 * @param array $options
	 *
	 * @return string The formatted HTML <a> element or empty string
	 */
	function oyo_get_link( $link_data, array $options = array() ): string {
		if ( ! is_array( $link_data ) || ! isset( $link_data['url'] ) ) {
			return '';
		}

		if ( ! isset( $link_data['title'] ) || empty( $link_data['title'] ) ) {
			$link_data['title'] = $link_data['url'];
		}

		if ( ! isset( $link_data['target'] ) ) {
			$link_data['target'] = '';
		}

		if ( isset( $options['class'] ) && is_string( $options['class'] ) ) {
			$class = $options['class'];
		} else {
			$class = '';
		}

		if ( isset( $options['id'] ) && is_string( $options['id'] ) ) {
			$id = $options['id'];
		} else {
			$id = '';
		}

		$replacements = array(
			'#url'    => $link_data['url'],
			'@title'  => $link_data['title'],
			':target' => $link_data['target'],
			':class'  => $class,
			':id'     => $id,
		);

		return oyo_format_string('
			<a href="#url" target=":target" class=":class" id=":id">
				@title
			</a>',
			$replacements
		);
	}
}
