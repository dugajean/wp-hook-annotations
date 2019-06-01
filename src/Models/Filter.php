<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

use function WpHookAnnotations\Helpers\get;

class Filter extends Model
{
    protected $priority;
    protected $acceptedArgs;

    public function __construct(array $data, $callable)
    {
        parent::__construct($data, $callable);

        $this->tag = get($data, 'tag');
        $this->priority = get($data, 'priority');
        $this->acceptedArgs = get($data, 'accepted_args');
    }

    public function trigger()
    {
        add_filter($this->tag, $this->callable, $this->priority, $this->acceptedArgs);
    }
}
