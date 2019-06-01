<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

use function WpHookAnnotations\Helpers\get;
use WpHookAnnotations\Exceptions\ArgumentNotFoundException;

/**
 * @Annotation
 */
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
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->tag = get($data, 'tag');
        $this->priority = get($data, 'priority', 10);
        $this->acceptedArgs = get($data, 'accepted_args', 1);
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
