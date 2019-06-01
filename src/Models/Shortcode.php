<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

class Shortcode extends Model
{
    /**
     * @var string
     */
    protected $handler = 'add_shortcode';

    /**
     * Shortcode constructor.
     *
     * @param array        $data
     * @param array|string $callable
     *
     * @throws \WpHookAnnotations\Exceptions\ArgumentNotFoundException
     */
    public function __construct(array $data, $callable)
    {
        parent::__construct($data, $callable);

        $this->tag = $data['tag'];
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
