<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use WpHookAnnotations\Exceptions\SyntaxException;
use function WpHookAnnotations\Helpers\class_basename;

class AnnotationParser extends Parser
{
    private $docBlockParser;
    protected $output = [
        'actions' => [],
        'filters' => [],
        'shortcodes' => []
    ];

    public function __construct(Parser $docBlockParser)
    {
        $this->docBlockParser = $docBlockParser;
    }

    public function parse()
    {
        $docBlock = $this->docBlockParser->parse()->get();

        foreach (explode("\n", $docBlock) as $line) {
            if ($this->ignoreFirstAndLast($line)) continue;

            foreach ($this->mapModels() as $key => $model) {
                $modelBase = class_basename($model);

                if (strpos($line, "@{$modelBase}(") === false) {
                    continue;
                }

                preg_match('#\((.*?)\)#', $line, $match);

                if (!($args = $match[1])) {
                    throw new SyntaxException('Invalid syntax detected: Cannot find proper parentheses.');
                }

                $args = @json_decode($args, true);

                if ($args === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new SyntaxException('Invalid syntax detected: Annotation arguments must be in JSON format.');
                }

                $this->output[$key][] = new $model($args, $this->docBlockParser->getCallable());
            }
        }

        return $this;
    }

    public function get()
    {
        return $this->output;
    }

    public function ignoreFirstAndLast(string $line)
    {
        $line = trim($line);

        return $line === '/**' || $line === '*/';
    }
}
