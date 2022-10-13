<?php

/**
 * Determine if the given value is "blank."
 */
function oyo_blank($value): bool
{
    if (is_null($value)) {
        return true;
    }

    if (is_string($value)) {
        return trim($value) === '';
    }

    if (is_numeric($value) || is_bool($value)) {
        return false;
    }

    if ($value instanceof Countable) {
        return count($value) === 0;
    }

    return empty($value);
}

/**
 * Get an HTML element's attribute value.
 *
 * Usage: oyo_element_attr(<a href="example.com">, 'href') yields example.com
 */
function oyo_element_attr(string $htmlEl, string $attr): ?string {
    if (oyo_blank($attr)) {
        throw new Exception("A valid attr must be provided.");
    }

    preg_match("/{$attr}=\"(.+?)\"/", $htmlEl, $matches);
    return $matches[1] ?? null;
}