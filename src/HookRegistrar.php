<?php

declare(strict_types=1);

namespace WpHookAnnotations;

use WpHookAnnotations\Models\Model;
use WpHookAnnotations\Parsers\AnnotationParser;
use Doctrine\Common\Annotations\AnnotationRegistry;

final class HookRegistrar
{
    /**
     * Call this method once somewhere early in your code.
     *
     * This method will be redundant soon:
     * @url https://github.com/doctrine/annotations/issues/182
     */
    public static function setup()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * Initialize a class to "listen" for annotations.
     *
     * @param object|string $object
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \WpHookAnnotations\Exceptions\InvalidCallableException
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
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \WpHookAnnotations\Exceptions\InvalidCallableException
     */
    public function register($callable)
    {
        $annotationParser = new AnnotationParser($callable);
        $modelsCollection = $annotationParser->getModels();
        
        array_walk(
            $modelsCollection,
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
