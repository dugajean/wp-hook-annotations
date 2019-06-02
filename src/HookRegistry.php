<?php

declare(strict_types=1);

namespace WpHookAnnotations;

use WpHookAnnotations\Models\Model;
use WpHookAnnotations\Parsers\AnnotationParser;

/**
 * Reads the annotations and registers the hooks.
 *
 * @author Dukagjin Surdulli <me@dukagj.in>
 */
final class HookRegistry
{
    /**
     * Initialize a class to "listen" for annotations.
     *
     * @param object|string $object
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \WpHookAnnotations\Exceptions\InvalidCallableException
     * @throws \WpHookAnnotations\Exceptions\TriggerNotFoundException
     */
    public function bootstrap($object)
    {
        if (!(is_string($object) && class_exists($object)) && !is_object($object)) {
            return;
        }

        $methods = (array)get_class_methods($object);

        foreach ($this->annotatedMethods($object, $methods) as $method) {
            $this->register([$object, $method]);
        }
    }

    /**
     * Parse the annotations and trigger the functions of the respective models.
     *
     * @param array $callable
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \WpHookAnnotations\Exceptions\InvalidCallableException
     * @throws \WpHookAnnotations\Exceptions\TriggerNotFoundException
     */
    public function register(array $callable)
    {
        $annotationParser = new AnnotationParser($callable);
        $modelsCollection = $annotationParser->getModels();

        foreach ($modelsCollection as $model) {
            $model->trigger();
        }
    }

    /**
     * Generator to retrieve methods with docblocks.
     *
     * @param mixed $class
     * @param array $methods
     *
     * @return \Generator
     */
    private function annotatedMethods($class, array $methods)
    {
        foreach ($methods as $key => $method) {
            try {
                $reflectionMethod = new \ReflectionMethod($class, $method);
            } catch (\ReflectionException $e) {
                continue;
            }

            if ($reflectionMethod->getDocComment() !== false) {
                yield $key => $method;
            }
        }
    }
}
