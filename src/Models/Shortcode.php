<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations\Models;

/**
 * @Annotation
 */
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
     * @throws \Dugajean\WpHookAnnotations\Exceptions\ArgumentNotFoundException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

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
