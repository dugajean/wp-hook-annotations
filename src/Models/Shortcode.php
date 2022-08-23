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
class Shortcode extends Model
{
    /**
     * @var string
     */
    protected $handler = 'add_shortcode';

    /**
     * Shortcode constructor.
     *
     * @param array $data
     *
     * @throws \Ari\WpHook\Exceptions\ArgumentNotFoundException
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
        $this->validateFields();
    }

    /**
     * Ordered indexed list of arguments expected by the trigger functions.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [$this->tag, $this->callable];
    }
}
