<?php

declare(strict_types=1);

namespace WpHookAnnotations\Helpers;

/**
 * Safely retrieve key from array.
 *
 * @param array  $array
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed|null
 */
function get(array $array, string $key, $default = null)
{
    if (!array_key_exists($key, $array)) {
        return $default;
    }

    return $array[$key];
}

/**
 * Get the class "basename" of the given object / class.
 *
 * @param  string|object $class
 * @return string
 */
function class_basename($class)
{
    $class = is_object($class) ? get_class($class) : $class;

    return basename(str_replace('\\', '/', $class));
}
