<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use ReflectionMethod;
use ReflectionFunction;
use ReflectionException;
use WpHookAnnotations\Models\Model;
use Doctrine\Common\Annotations\AnnotationReader;
use WpHookAnnotations\Exceptions\InvalidCallableException;

class AnnotationParser
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $reflectionFunction;

    /**
     * @var array|string
     */
    private $callable;

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
        } else {
            throw new InvalidCallableException;
        }
    }

    /**
     * Parse the docblock and return an array for each line.
     *
     * @return array
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getModels(): array
    {
        $reader = new AnnotationReader();
        $annotations = $reader->getMethodAnnotations($this->reflectionFunction);

        $annotations = array_map(function (Model $item) {
            return $item->setCallable($this->callable);
        }, $annotations);

        return $annotations;
    }
}
