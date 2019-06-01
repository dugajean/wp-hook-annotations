<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

use function WpHookAnnotations\Helpers\get;

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
     * @param array        $data
     * @param array|string $callable
     *
     * @throws \WpHookAnnotations\Exceptions\ArgumentNotFoundException
     */
    public function __construct(array $data, $callable)
    {
        parent::__construct($data, $callable);

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
