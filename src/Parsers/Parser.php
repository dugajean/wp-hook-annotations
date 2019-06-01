<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use WpHookAnnotations\Models\Action;
use WpHookAnnotations\Models\Filter;
use WpHookAnnotations\Models\Shortcode;

abstract class Parser
{
    /**
     * @var mixed
     */
    protected $output;

    /**
     * @var array|string
     */
    protected $callable;

    /**
     * Fetch the output of the parsing process.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->output;
    }

    /**
     * List of models we are working with and their respective class paths.
     *
     * @return array
     */
    protected function mapModels(): array
    {
        return [
            'actions' => Action::class,
            'filters' => Filter::class,
            'shortcodes' => Shortcode::class,
        ];
    }

    /**
     * Getter for the callable property.
     *
     * @return array|string
     */
    protected function getCallable()
    {
        return $this->callable;
    }

    /**
     * The parsing algorithm.
     *
     * @return $this
     */
    abstract public function parse();
}
