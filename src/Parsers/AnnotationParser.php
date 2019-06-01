<?php

declare(strict_types=1);

namespace WpHookAnnotations\Parsers;

use WpHookAnnotations\Exceptions\SyntaxException;
use function WpHookAnnotations\Helpers\class_basename;

class AnnotationParser extends Parser
{
    /**
     * @var Parser
     */
    private $docBlockParser;

    /**
     * @var array
     */
    protected $output = [
        'actions' => [],
        'filters' => [],
        'shortcodes' => []
    ];

    /**
     * AnnotationParser constructor.
     *
     * @param Parser $docBlockParser
     */
    public function __construct(Parser $docBlockParser)
    {
        $this->docBlockParser = $docBlockParser;
    }

    /**
     * Parse actions, filters and shortcodes from docblocks.
     *
     * @return $this
     *
     * @throws SyntaxException
     */
    public function parse(): self
    {
        $docBlockLines = $this->docBlockParser->parse()->get();
        
        foreach ((array)$docBlockLines as $line) {
            if ($this->ignoreFirstAndLast($line)) continue;

            foreach ($this->mapModels() as $key => $model) {
                $modelBase = class_basename($model);

                // Check if we have an annotation that interests us
                if (strpos($line, "@{$modelBase}(") === false) {
                    continue;
                }

                // Fetch the JSON between the brackets.
                preg_match('#\((.*?)\)#', $line, $match);

                if (!isset($match[1])) {
                    throw new SyntaxException('Invalid syntax detected: Cannot find proper opening or closing brackets.');
                }

                // Parse the JSON
                $args = @json_decode($match[1], true);

                if ($args === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new SyntaxException('Invalid syntax detected: Annotation arguments must be in JSON format.');
                }

                $this->output[$key][] = new $model($args, $this->docBlockParser->getCallable());
            }
        }

        return $this;
    }

    /**
     * Determine whether we are on the first or last line of the docblock.
     *
     * @param string $line
     *
     * @return bool
     */
    public function ignoreFirstAndLast(string $line): bool
    {
        $line = trim($line);

        return $line === '/**' || $line === '*/';
    }
}
