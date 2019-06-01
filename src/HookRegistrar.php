<?php

declare(strict_types=1);

namespace WpHookAnnotations;

use WpHookAnnotations\Models\Model;
use WpHookAnnotations\Parsers\DocBlockParser;
use WpHookAnnotations\Parsers\AnnotationParser;
use WpHookAnnotations\Exceptions\SyntaxException;
use WpHookAnnotations\Exceptions\InvalidCallableException;

final class HookRegistrar
{
    /**
     * Initialize a class to "listen" for annotations.
     *
     * @param object|string $object
     *
     * @throws SyntaxException
     * @throws InvalidCallableException
     */
    public function bootstrap($object)
    {
        if ((is_string($object) && class_exists($object)) || is_object($object)) {
            $methods = get_class_methods($object);
            foreach ((array)$methods as $method) {
                if ($this->hasAnnotations($object, $method)) {
                    $this->register([$object, $method]);
                }
            }
        }
    }

    /**
     * Parse the annotations and trigger the functions of the respective models.
     *
     * @param $callable
     *
     * @throws SyntaxException
     * @throws InvalidCallableException
     */
    public function register($callable)
    {
        $annotationParser = new AnnotationParser(new DocBlockParser($callable));
        $modelsNested = $annotationParser->parse()->get();

        array_walk_recursive(
            $modelsNested,
            function(Model $model) { $model->trigger(); }
        );
    }

    /**
     * Determine whether the method has annotations.
     *
     * @param mixed  $class
     * @param string $method
     *
     * @return bool
     */
    private function hasAnnotations($class, $method): bool
    {
        try {
            $reflectionMethod = new \ReflectionMethod($class, $method);
        } catch (\ReflectionException $e) {
            return false;
        }

        return $reflectionMethod->getDocComment() !== false;
    }
}
