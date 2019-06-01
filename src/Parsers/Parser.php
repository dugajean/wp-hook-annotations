<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use WpHookAnnotations\Models\Action;
use WpHookAnnotations\Models\Filter;
use WpHookAnnotations\Models\Shortcode;

abstract class Parser
{
    protected $output;
    protected $callable;

    abstract public function parse();

    public function get()
    {
        return $this->output;
    }

    protected function mapModels()
    {
        return [
            'actions' => Action::class,
            'filters' => Filter::class,
            'shortcodes' => Shortcode::class,
        ];
    }

    protected function getCallable()
    {
        return $this->callable;
    }
}
