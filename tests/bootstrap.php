<?php

$vendor = __DIR__.'/../vendor/';

if (!file_exists($vendor)) {
    die('Please run composer install before triggering the tests.');
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
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        echo json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
            'output' => execute_func($function_to_add),
        ]);
    }
}

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        echo json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
            'output' => execute_func($function_to_add),
        ]);
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback)
    {
        echo json_encode([
            'function' => __FUNCTION__,
            'tag' => $tag,
            'output' => execute_func($callback),
        ]);
    }
}
