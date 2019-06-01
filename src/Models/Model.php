<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

use WpHookAnnotations\Exceptions\ArgumentNotFoundException;

abstract class Model
{
    protected $tag;
    protected $callable;
    protected $requiredArguments = ['tag'];

    public function __construct(array $data, $callable)
    {
        $this->validateFields($data);
        $this->callable = $callable;
    }

    public function validateFields(array $data)
    {
        foreach ($this->requiredArguments as $argument) {
            if (!in_array($argument, array_keys($data))) {
                throw new ArgumentNotFoundException("Required argument '{$argument}' not found in annotation");
            }
        }
    }

    abstract public function trigger();
}
