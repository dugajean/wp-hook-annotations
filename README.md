# WP Hook Annotations

[![Latest Unstable Version](https://poser.pugx.org/dugajean/wp-hook-annotations/v/unstable)](https://packagist.org/packages/dugajean/wp-hook-annotations)
[![Total Downloads](https://poser.pugx.org/dugajean/wp-hook-annotations/downloads)](https://packagist.org/packages/dugajean/wp-hook-annotations) 
[![License](https://poser.pugx.org/dugajean/wp-hook-annotations/license)](https://packagist.org/packages/dugajean/wp-hook-annotations) 

Use PHP Docblock (annotations) to register WordPress hooks, filters and shortcodes.

## Requirements

- PHP 7.1+

## Install

Via Composer

```bash
$ composer require dugajean/wp-hook-annotations
```

## Usage

To automatically wire up your class, simply call the `HookRegistrar::bootstrap` method, like so: 

```php
<?php

namespace My\CoolNamespace;

use WpHookAnnotations\HookRegistrar;

// Add this somewhere and only run it once
HookRegistrar::setup();

class MyClass
{
    public function __construct(HookRegistrar $hookRegistrar) 
    {
        $hookRegistrar->bootstrap($this);
    }
    
    /**
     * @Action(tag="init")    
     */
    public function doSomething()
    {
        // do something
    }
}
```

And you're done!

The following annotations can be used:

```php
/**
 * @Action(tag="the hook name", priority=1, accepted_args=1)
 * @Filter(tag="the filter name", priority=1, accepted_args=1)
 * @Shortcode(tag="the shortcode code")
 */
```

## Testing

```bash
$ vendor/bin/phpunit
```

## License
WP Hook Annotations is released under [the MIT License](LICENSE).
