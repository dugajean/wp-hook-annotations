<?php

declare(strict_types=1);

namespace WpHookAnnotations\Tests\Unit;

use WpHookAnnotations\HookRegistrar;
use WpHookAnnotations\Tests\TestCase;
use WpHookAnnotations\Exceptions\SyntaxException;
use WpHookAnnotations\Exceptions\ArgumentNotFoundException;

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
        $sampleClass = new class() {
            /**
             * @Action({"tag":"init"})
             */
            public function foo() {
                return 'Foo';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');
        
        $this->assertEquals($this->generateExpectedActionOrFilter('init', 'Foo'), $output);
    }

    public function test_filter_annotation_on_method()
    {
        $sampleClass = new class() {
            /**
             * @Filter({"tag":"wp_title"})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter'), $output);
    }

    public function test_filter_with_more_args_annotation_on_method()
    {
        $sampleClass = new class() {
            /**
             * @Filter({"tag":"wp_title", "priority": 99})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99), $output);
    }

    public function test_filter_with_more_args_annotation_on_method2()
    {
        $sampleClass = new class() {
            /**
             * @Filter({"tag":"wp_title", "priority": 99, "accepted_args":2})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99, 2), $output);
    }

    public function test_with_multiple_annotations_in_method()
    {
        $sampleClass = new class() {
            /**
             * @Action({"tag":"init", "priority": 99, "accepted_args":2})
             * @Filter({"tag":"wp_title"})
             * @Shortcode({"tag":"my_shortcode"})
             */
            public function foo() {
                return 'Foo';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');
        
        $expected = $this->generateExpectedActionOrFilter('init', 'Foo', 'add_action', 99, 2) .
            $this->generateExpectedActionOrFilter('wp_title', 'Foo', 'add_filter') .
            $this->generateExpectedShortcode('my_shortcode', 'Foo');

        $this->assertEquals($expected, $output);
    }
    
    public function test_wiring_up_class_via_constructor()
    {
        $hookRegistrar = $this->hookRegistrar;

        ob_start();

        new class($hookRegistrar) {
            public function __construct(HookRegistrar $hookRegistrar) {
                $hookRegistrar->bootstrap($this);
            }

            /**
             * @Action({"tag":"init", "priority": 99, "accepted_args":2})
             * @Filter({"tag":"wp_title"})
             * @Shortcode({"tag":"my_shortcode"})
             */
            public function foo() {
                return 'Foo';
            }

            /**
             * @Shortcode({"tag":"cool_shortcode"})
             */
            public function bar() {
                return 'Bar';
            }

            /**
             * @Filter({"tag":"wp_title", "priority": 99, "accepted_args":2})
             */
            public function baz() {
                return 'Baz';
            }
        };

        $output = ob_get_contents();
        ob_end_clean();

        $expected = $this->generateExpectedActionOrFilter('init', 'Foo', 'add_action', 99, 2) .
            $this->generateExpectedActionOrFilter('wp_title', 'Foo', 'add_filter') .
            $this->generateExpectedShortcode('my_shortcode', 'Foo') .
            $this->generateExpectedShortcode('cool_shortcode', 'Bar') .
            $this->generateExpectedActionOrFilter('wp_title', 'Baz', 'add_filter', 99, 2);
        
        $this->assertEquals($expected, $output);
    }

    public function test_filter_annotation_with_reordered_args()
    {
        $sampleClass = new class() {
            /**
             * @Filter({"accepted_args":2, "tag":"wp_title", "priority": 99})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');

        $this->assertEquals($this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99, 2), $output);
    }

    public function test_filter_annotation_with_bad_optional_arg()
    {
        $sampleClass = new class() {
            /**
             * @Filter({"foo":2, "tag":"wp_title", "priority": 99})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');
        $expected = $this->generateExpectedActionOrFilter('wp_title', 'New Title', 'add_filter', 99, 1);

        $this->assertEquals($expected, $output);
    }

    public function test_filter_annotation_with_extra_args()
    {
        $sampleClass = new class() {
            /**
             * @Shortcode({"foo":2, "tag":"best_shortcode_ever"})
             */
            public function foo() {
                return 'Holy Shortcode!';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');
        $expected = $this->generateExpectedShortcode('best_shortcode_ever', 'Holy Shortcode!');

        $this->assertEquals($expected, $output);
    }

    public function test_with_oneline_docblock()
    {
        $sampleClass = new class() {
            /* @Shortcode({"foo":2, "tag":"best_shortcode_ever"}) */
            public function foo() {
                return 'Holy Shortcode!';
            }
        };

        $class = new $sampleClass;
        $output = $this->registerHook($class, 'foo');

        $this->assertEmpty($output);
    }

    public function test_filter_annotation_with_no_required_arg()
    {
        $this->expectException(ArgumentNotFoundException::class);

        $sampleClass = new class() {
            /**
             * @Filter({"accepted_args":2, "foo":"wp_title", "priority": 99})
             */
            public function foo() {
                return 'New Title';
            }
        };

        $class = new $sampleClass;
        $this->registerHook($class, 'foo', false);
    }

    public function test_filter_with_malformed_annotation()
    {
        $this->expectException(SyntaxException::class);

        $sampleClass = new class() {
            /**
             * @Shortcode({"foo":2, "tag":"best_shortcode_ever"}
             */
            public function foo() {
                return 'Holy Shortcode!';
            }
        };

        $class = new $sampleClass;
        $this->registerHook($class, 'foo', false);
    }

    public function test_filter_with_malformed_json_annotation()
    {
        $this->expectException(SyntaxException::class);

        $sampleClass = new class() {
            /**
             * @Shortcode({"foo"=2, "tag":"best_shortcode_ever"})
             */
            public function foo() {
                return 'Holy Shortcode!';
            }
        };

        $class = new $sampleClass;
        $this->registerHook($class, 'foo', false);
    }
}
