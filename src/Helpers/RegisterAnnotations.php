<?php

declare(strict_types=1);

namespace Ari\WpHook\Helpers;

use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * This call will be redundant soon:
 * @url https://github.com/doctrine/annotations/issues/182
 */
AnnotationRegistry::registerLoader('class_exists');
