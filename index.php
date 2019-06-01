<?php

require __DIR__ . '/vendor/autoload.php';

use WpHookAnnotations\HookManager;

function execute_func($function)
{
    if (is_array($function)) {
        return call_user_func_array($function, []);
    }

    return $function();
}

function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    echo __FUNCTION__ . PHP_EOL;
    echo sprintf('[tag => %s, priority => %s, accepted_args => %s]', $tag, $priority, $accepted_args) . PHP_EOL;
    echo execute_func($function_to_add) . PHP_EOL;
}

function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    echo __FUNCTION__ . PHP_EOL;
    add_filter($tag, $function_to_add, $priority, $accepted_args);
}

function add_shortcode($tag, $callback)
{
    echo __FUNCTION__ . PHP_EOL;
    echo sprintf('[tag => %s]', $tag) . PHP_EOL;
    echo execute_func($callback) . PHP_EOL;
}

class MyTest
{
    /**
     * @Action({"tag":"init", "priority":10, "accepted_args":1})
     * @Action({"tag":"something_else", "priority":100, "accepted_args":1})
     * @Filter({"tag":"best_filta", "priority":100, "accepted_args":1})
     */
    public function superMethod()
    {
        return 'Hello World';
    }
}

(new HookManager)->setup(['MyTest', 'superMethod']);
