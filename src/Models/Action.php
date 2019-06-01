<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

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
