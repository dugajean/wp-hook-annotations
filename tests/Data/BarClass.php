<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations\Tests\Data;

use Dugajean\WpHookAnnotations\HookRegistry;
use Dugajean\WpHookAnnotations\Models\Action;
use Dugajean\WpHookAnnotations\Models\Filter;
use Dugajean\WpHookAnnotations\Models\Shortcode;

class BarClass
{
    public function __construct(HookRegistry $hookRegistrar) {
        $hookRegistrar->bootstrap($this);
    }

    /**
     * @Action(tag="init", priority=99, accepted_args=2)
     * @Filter(tag="wp_title")
     * @Shortcode(tag="my_shortcode")
     */
    public function foo() {
        return 'Foo';
    }

    /**
     * @Shortcode(tag="cool_shortcode")
     */
    public function bar() {
        return 'Bar';
    }

    /**
     * @Filter(tag="wp_title", priority= 99, accepted_args=2)
     */
    public function baz() {
        return 'Baz';
    }
}
