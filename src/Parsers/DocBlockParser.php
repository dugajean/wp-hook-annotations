<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use ReflectionException;
use ReflectionMethod;
use ReflectionFunction;
use WpHookAnnotations\Exceptions\InvalidCallableException;

class DocBlockParser extends Parser
{
    protected $output;
    protected $reflectionFunction;

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

    public function parse()
    {
        if (!($docblock = $this->reflectionFunction->getDocComment())) {
            return $this;
        }

        $this->output = str_replace('    ', '', $docblock);
        $this->output = str_replace("\t", '', $docblock);

        return $this;
    }
}
