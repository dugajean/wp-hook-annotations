<?php

declare(strict_types=1);

namespace Ari\WpHook\Models;

use Ari\WpHook\Exceptions\TriggerNotFoundException;
use Ari\WpHook\Exceptions\ArgumentNotFoundException;

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
    protected function validateFields()
    {
        foreach ($this->requiredArguments as $argument) {
            if (empty($this->{$argument})) {
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
