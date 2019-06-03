<?php

declare(strict_types=1);

namespace Dugajean\WpHookAnnotations;

use Dugajean\WpHookAnnotations\Parsers\AnnotationParser;

/**
 * Reads the annotations and registers the hooks.
 *
 * @author Dukagjin Surdulli <me@dukagj.in>
 */
final class HookRegistry
{
    /**
     * Bootstrap a list of classes.
     *
     * @param array             $classes
     * @param HookRegistry|null $registry
     */
    public static function bootstrapClasses(array $classes, ?HookRegistry $registry = null)
    {
        $classes = array_filter($classes, 'class_exists');
        $registry = $registry ?: new self;

        foreach ($classes as $class) {
            $registry->bootstrap($class);
        }
    }

    /**
     * Initialize a class to "listen" for annotations.
     *
     * @param object|string $object
     */
    public function bootstrap($object)
    {
        if (!(is_string($object) && class_exists($object)) && !is_object($object)) {
            return;
        }

        $methods = (array)get_class_methods($object);

        foreach ($this->annotatedMethods($object, $methods) as $method) {
            try {
                $this->register([$object, $method]);
            } catch (\Exception $e) {
                if (function_exists('wp_die')) {
                    wp_die('Could not register hooks with annotations. Please submit an issue.');
                } else {
                    return;
                }
            }
        }
    }

    /**
     * Parse the annotations and trigger the functions of the respective models.
     *
     * @param array $callable
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Dugajean\WpHookAnnotations\Exceptions\InvalidCallableException
     * @throws \Dugajean\WpHookAnnotations\Exceptions\TriggerNotFoundException
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
