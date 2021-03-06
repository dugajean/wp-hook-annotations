<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations\Models;

use Dugajean\WpHookAnnotations\Exceptions\TriggerNotFoundException;
use Dugajean\WpHookAnnotations\Exceptions\ArgumentNotFoundException;

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
     * @var array
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
     *
     * @throws \Dugajean\WpHookAnnotations\Exceptions\ArgumentNotFoundException
     */
    public function __construct(array $data)
    {
        $this->validateFields($data);
    }

    /**
     * Trigger the WordPress function to handle the registration.
     *
     * @throws TriggerNotFoundException
     */
    public function trigger()
    {
        if (!$this->handler && !function_exists($this->handler)) {
            throw new TriggerNotFoundException("Function '{$this->handler}' could not be executed");
        }
    
        ($this->handler)(...$this->arguments());
    }

    /**
     * Set the callable (mutable).
     *
     * @param $callable
     *
     * @return $this
     */
    public function setCallable($callable): self
    {
        $this->callable = $callable;

        return $this;
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
                    'Required argument "%s" not found in annotation.',
                    $argument
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
