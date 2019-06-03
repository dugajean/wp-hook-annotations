<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations\Models;

/**
 * @Annotation
 */
class Action extends Filter
{
    /**
     * @var string
     */
    protected $handler = 'add_action';
}
