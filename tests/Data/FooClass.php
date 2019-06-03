<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations\Tests\Data;

use Dugajean\WpHookAnnotations\Models\Action;
use Dugajean\WpHookAnnotations\Models\Filter;
use Dugajean\WpHookAnnotations\Models\Shortcode;

class FooClass
{
    /**
     * @Action(tag="init", priority= 99, accepted_args=2)
     * @Filter(tag="wp_title")
     * @Shortcode(tag="my_shortcode")
     */
    public function foo()
    {
        return 'Foo';
    }

    /**
     * @Filter(tag="wp_title")
     */
    public function foo1()
    {
        return 'New Title';
    }

    /**
     * @Filter(tag="wp_title", priority=99)
     */
    public function foo3()
    {
        return 'New Title';
    }

    /**
     * @Filter(tag="wp_title", priority=99, accepted_args=2)
     */
    public function foo4()
    {
        return 'New Title';
    }

    /**
     * @Action(tag="init")
     */
    public function foo5()
    {
        return 'Foo';
    }
}
