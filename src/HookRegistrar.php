<?php

declare(strict_types=1);

namespace WpHookAnnotations;

use WpHookAnnotations\Parsers\DocBlockParser;
use WpHookAnnotations\Parsers\AnnotationParser;
use WpHookAnnotations\Exceptions\SyntaxException;
use WpHookAnnotations\Exceptions\InvalidCallableException;

class HookRegistrar
{
    /**
     * Parse the annotations and trigger the functions of the respective models.
     *
     * @param $callable
     *
     * @throws SyntaxException
     * @throws InvalidCallableException
     */
    public function setup($callable)
    {
        $annotationParser = new AnnotationParser(new DocBlockParser($callable));
        $modelsNested = $annotationParser->parse()->get();

        $models = [];
        array_walk_recursive(
            $modelsNested,
            function($v) use (&$models) { $models[] = $v; }
        );

        foreach ($models as $model) {
            $model->trigger();
        }
    }
}
