<?php

declare(strict_types=1);

namespace WpHookAnnotations\Tests\Unit;

use WpHookAnnotations\Tests\TestCase;
use WpHookAnnotations\Tests\Data\BarClass;
use WpHookAnnotations\Tests\Data\FooClass;

class RegisterTest extends TestCase
{
    public function test_if_functions_are_reachable()
    {
        $this->assertTrue(function_exists('add_filter'));
        $this->assertTrue(function_exists('add_action'));
        $this->assertTrue(function_exists('add_shortcode'));
    }

    public function test_action_annotation_on_method()
    {
        $output = $this->registerHook(FooClass::class, 'foo5');
        
        $this->assertEquals($this->generateExpectedActionOrFilter('init', 'Foo'), $output);
    }

    public function test_filter_annotation_on_method()
    {
        $output = $this->registerHook(FooClass::class, 'foo1');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter'), $output);
    }

    public function test_filter_with_more_args_annotation_on_method()
    {
        $output = $this->registerHook(FooClass::class, 'foo3');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99), $output);
    }

    public function test_filter_with_more_args_annotation_on_method2()
    {

        $output = $this->registerHook(FooClass::class, 'foo4');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99, 2), $output);
    }

    public function test_with_multiple_annotations_in_method()
    {
        $output = $this->registerHook(FooClass::class, 'foo');
        
        $expected = $this->generateExpectedActionOrFilter('init', 'Foo', 'add_action', 99, 2) .
            $this->generateExpectedActionOrFilter('wp_title', 'Foo', 'add_filter') .
            $this->generateExpectedShortcode('my_shortcode', 'Foo');

        $this->assertEquals($expected, $output);
    }
    
    public function test_wiring_up_class_via_constructor()
    {
        $hookRegistrar = $this->hookRegistrar;

        ob_start();

        new BarClass($hookRegistrar);
        $output = ob_get_contents();

        ob_end_clean();

        $expected = $this->generateExpectedActionOrFilter('init', 'Foo', 'add_action', 99, 2) .
            $this->generateExpectedActionOrFilter('wp_title', 'Foo', 'add_filter') .
            $this->generateExpectedShortcode('my_shortcode', 'Foo') .
            $this->generateExpectedShortcode('cool_shortcode', 'Bar') .
            $this->generateExpectedActionOrFilter('wp_title', 'Baz', 'add_filter', 99, 2);
        
        $this->assertEquals($expected, $output);
    }
}
