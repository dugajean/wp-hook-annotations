# Pouch

[![Latest Stable Version](https://poser.pugx.org/dugajean/wp-hook-annotations/v/stable)](https://packagist.org/packages/dugajean/wp-hook-annotations) 
[![Total Downloads](https://poser.pugx.org/dugajean/wp-hook-annotations/downloads)](https://packagist.org/packages/dugajean/wp-hook-annotations) 
[![License](https://poser.pugx.org/dugajean/wp-hook-annotations/license)](https://packagist.org/packages/dugajean/wp-hook-annotations) 

Tiny IoC container with awesome autowiring & more - for your PHP project.

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

namespace My\Namespace

use WpHookAnnotations\HookRegistrar;

class MyClass
{
    public function __construct(HookRegistrar $hookRegistrar) 
    {
        $hookRegistrar->bootstrap($this);
    }
    
    /**
     * @Action({"tag":"init"})    
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
 * @Action({"tag":"the hook name", "priority":1, "accepted_args": 1})
 * @Filter({"tag":"the filter name", "priority":1, "accepted_args": 1})
 * @Shortcode({"tag":"the shortcode code"})
 */
```

## Testing

```bash
$ vendor/bin/phpunit
```

## License
Pouch is released under [the MIT License](LICENSE).
