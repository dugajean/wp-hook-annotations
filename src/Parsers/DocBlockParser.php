<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use ReflectionException;
use ReflectionMethod;
use ReflectionFunction;
use WpHookAnnotations\Exceptions\InvalidCallableException;

class DocBlockParser extends Parser
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $reflectionFunction;

    /**
     * DocBlockParser constructor.
     *
     * @param array|string $callable
     *
     * @throws InvalidCallableException
     */
    public function __construct($callable)
    {
        $this->callable = $callable;

        if (is_array($callable) && count($callable) === 2) {
            [$class, $method] = $callable;

            try {
                $this->reflectionFunction = new ReflectionMethod($class, $method);
            } catch (ReflectionException $e) {
                throw new InvalidCallableException;
            }
        } elseif (is_string($callable) && function_exists($callable)) {
            try {
                $this->reflectionFunction = new ReflectionFunction($callable);
            } catch (ReflectionException $e) {
                throw new InvalidCallableException;
            }
        } else {
            throw new InvalidCallableException;
        }
    }

    /**
     * Parse the docblock and return an array for each line.
     *
     * @return $this
     */
    public function parse(): self
    {
        if (!($docblock = $this->reflectionFunction->getDocComment())) {
            return $this;
        }

        $docblock = str_replace('    ', '', $docblock);
        $docblock = str_replace("\t", '', $docblock);

        $this->output = explode("\n", $docblock);

        return $this;
    }
}
