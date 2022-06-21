<?php

declare(strict_types=1);

namespace Ari\WpHook\Parsers;

use ReflectionMethod;
use ReflectionException;
use Ari\WpHook\Models\Model;
use Doctrine\Common\Annotations\AnnotationReader;
use Ari\WpHook\Exceptions\InvalidCallableException;

/**
 * Parses the annotations from the docblocks with Doctrine Annotations.
 *
 * @author Dukagjin Surdulli <me@dukagj.in>
 */
class HookParser
{
    /**
     * @var \ReflectionMethod
     */
    protected $reflectionMethod;

    /**
     * @var array
     */
    private $callable;

    /**
     * AnnotationParser constructor.
     *
     * @param array $callable
     *
     * @throws InvalidCallableException
     */
    public function __construct(array $callable)
    {
        $this->callable = $callable;
        $this->reflectionMethod = $this->getReflectionMethod();
    }

    /**
     * Parse the docblock and return an array for each line.
     *
     * @return Model[]
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getModels(): array
    {
        if (PHP_VERSION_ID >= 80000){
            return array_map(function (\ReflectionAttribute $item) {
                return ($item->newInstance())->setCallable($this->callable);
            }, $this->reflectionMethod->getAttributes());
        }

        return array_map(function (Model $item) {
            return $item->setCallable($this->callable);
        }, (new AnnotationReader())->getMethodAnnotations($this->reflectionMethod));
    }

    /**
     * Instantiates reflection method from array pieces.
     *
     * @throws InvalidCallableException
     */
    private function getReflectionMethod()
    {
        if (is_array($this->callable) && count($this->callable) === 2) {
            [$class, $method] = $this->callable;

            try {
                return new ReflectionMethod($class, $method);
            } catch (ReflectionException $exception) {
                throw new InvalidCallableException;
            }
        } else {
            throw new InvalidCallableException;
        }
    }
}
