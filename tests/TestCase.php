<?php

namespace WpHookAnnotations\Tests;

use WpHookAnnotations\HookRegistrar;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        HookRegistrar::setup();
    }

    /**
     * @var HookRegistrar
     */
    protected $hookRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hookRegistrar = new HookRegistrar;
    }

    protected function registerHook($class, $method, $buffer = true): ?string
    {
        if ($buffer) {
            ob_start();
        }

        $this->hookRegistrar->register([$class, $method]);

        if ($buffer) {
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        return null;
    }

    public function generateExpectedActionOrFilter($tag, $output, $function = 'add_action', $priority = 10, $acceptedArgs = 1): string
    {
        $expected = [
            'function' => $function,
            'tag' => $tag,
            'priority' => $priority,
            'accepted_args' => $acceptedArgs,
            'output' => $output,
        ];

        return json_encode($expected);
    }
    
    public function generateExpectedShortcode($tag, $output)
    {
        $expected = [
            'function' => 'add_shortcode',
            'tag' => $tag,
            'output' => $output,
        ];

        return json_encode($expected);
    }
}
