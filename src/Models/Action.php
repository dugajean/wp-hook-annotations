<?php

declare(strict_types=1);

namespace Ari\WpHook\Models;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class Action extends Filter
{
    /**
     * @var string
     */
    protected $handler = 'add_action';
}
