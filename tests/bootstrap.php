<?php

$vendor = __DIR__.'/../vendor/';

if (!file_exists($vendor)) {
    print_r('Please run composer install before triggering the tests.');
    exit();
}

require_once $vendor.'autoload.php';

if (!function_exists('execute_func')) {
    function execute_func($function)
    {
        if (is_array($function)) {
            return call_user_func_array($function, []);
        }

        return $function();
    }
}

if (!function_exists('add_filter')){
    /**
     * @throws JsonException
     */
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        print_r(json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
            'output' => execute_func($function_to_add),
        ], JSON_THROW_ON_ERROR));
    }
}

if (!function_exists('add_action')) {
    /**
     * @throws JsonException
     */
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        print_r(json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
            'output' => execute_func($function_to_add),
        ], JSON_THROW_ON_ERROR));
    }
}

if (!function_exists('add_shortcode')) {
    /**
     * @throws JsonException
     */
    function add_shortcode($tag, $callback)
    {
        print_r(json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'output' => execute_func($callback),
        ], JSON_THROW_ON_ERROR));
    }
}
