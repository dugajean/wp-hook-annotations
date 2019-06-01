<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

use function WpHookAnnotations\Helpers\normalize_function;
use WpHookAnnotations\Exceptions\TriggerNotFoundException;
use WpHookAnnotations\Exceptions\ArgumentNotFoundException;

abstract class Model
{
    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string
     */
    protected $handler;

    /**
     * @var array|string
     */
    protected $callable;

    /**
     * @var array
     */
    protected $requiredArguments = ['tag'];

    /**
     * Model constructor.
     *
     * @param array        $data
     * @param array|string $callable
     *
     * @throws \WpHookAnnotations\Exceptions\ArgumentNotFoundException
     */
    public function __construct(array $data, $callable)
    {
        $this->validateFields($data);

        $this->callable = $callable;
    }

    /**
     * Trigger the WordPress function to handle the registration.
     *
     * @throws TriggerNotFoundException
     */
    public function trigger()
    {
        if (!$this->handler && !function_exists($this->handler)) {
            throw new TriggerNotFoundException;
        }
    
        ($this->handler)(...$this->arguments());
    }

    /**
     * Determine if the required arguments set in requiredArguments are present.
     *
     * @param array $data
     *
     * @throws ArgumentNotFoundException
     */
    protected function validateFields(array $data)
    {
        foreach ($this->requiredArguments as $argument) {
            if (!in_array($argument, array_keys($data))) {
                throw new ArgumentNotFoundException(sprintf(
                    'Required argument "%s" not found in annotation for function: %s',
                    $argument,
                    normalize_function($this->callable)
                ));
            }
        }
    }

    /**
     * Ordered indexed list of arguments expected by the trigger functions.
     *
     * @return array
     */
    abstract protected function arguments(): array;
}
