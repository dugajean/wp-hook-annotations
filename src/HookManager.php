<?php

declare(strict_types=1);

namespace WpHookAnnotations;

use WpHookAnnotations\Parsers\DocBlockParser;
use WpHookAnnotations\Parsers\AnnotationParser;

class HookManager
{
    public function setup($callable)
    {
        $docBlockParser = new DocBlockParser($callable);
        $annotationParser = new AnnotationParser($docBlockParser);
        $modelsNested = $annotationParser->parse()->get();

        $models = [];
        array_walk_recursive(
            $modelsNested,
            function($v) use (&$models) {
            $models[] = $v;
        });

        foreach ($models as $model) {
            $model->trigger();
        }
    }
}
