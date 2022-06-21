<?php

declare(strict_types=1);

namespace Ari\WpHook\Models;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use function Ari\WpHook\Helpers\get;
use Ari\WpHook\Exceptions\ArgumentNotFoundException;
use Attribute;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class Filter extends Model
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * @var int
     */
    protected $acceptedArgs;

    /**
     * @var string
     */
    protected $handler = 'add_filter';

    /**
     * Filter constructor.
     *
     * @param array $data
     *
     * @throws ArgumentNotFoundException
     */
    public function __construct(string $tag, int $priority = 10, int $accepted_args = 1)
    {
        $this->tag = $tag;
        $this->priority = $priority;
        $this->acceptedArgs = $accepted_args;

        $this->validateFields();
    }

    /**
     * Ordered indexed list of arguments expected by the trigger functions.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [$this->tag, $this->callable, $this->priority, $this->acceptedArgs];
    }
}
