<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

class Shortcode extends Model
{
    protected $handler = 'add_shortcode';

    public function __construct(array $data, $callable)
    {
        parent::__construct($data, $callable);

        $this->tag = $data['tag'];
    }

    public function trigger()
    {
        add_shortcode($this->tag, $this->callable);
    }
}
