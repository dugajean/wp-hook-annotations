<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use ReflectionMethod;
use ReflectionException;
use WpHookAnnotations\Models\Model;
use Doctrine\Common\Annotations\AnnotationReader;
use WpHookAnnotations\Exceptions\InvalidCallableException;

/**
 * Parses the annotations from the docblocks with Doctrine Annotations.
 *
 * @author Dukagjin Surdulli <me@dukagj.in>
 */
class AnnotationParser
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
        $reader = new AnnotationReader();
        $annotations = $reader->getMethodAnnotations($this->reflectionMethod);

        $annotations = array_map(function (Model $item) {
            return $item->setCallable($this->callable);
        }, $annotations);

        return $annotations;
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
                $reflectionMethod = new ReflectionMethod($class, $method);
            } catch (ReflectionException $e) {
                throw new InvalidCallableException;
            }
        } else {
            throw new InvalidCallableException;
        }

        return $reflectionMethod;
    }
}
