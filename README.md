# WP Hook

[![PHPUnit](https://github.com/arnaud-ritti/wp-hook/actions/workflows/php.yml/badge.svg)](https://github.com/arnaud-ritti/wp-hook/actions/workflows/php.yml)
[![Latest Unstable Version](https://poser.pugx.org/dugajean/wp-hook-annotations/v/unstable)](https://packagist.org/packages/arnaud-ritti/wp-hook)
[![Total Downloads](https://poser.pugx.org/arnaud-ritti/wp-hook/downloads)](https://packagist.org/packages/arnaud-ritti/wp-hook) 
[![License](https://poser.pugx.org/arnaud-ritti/wp-hook/license)](https://packagist.org/packages/arnaud-ritti/wp-hook) 

Register WordPress hooks, filters and shortcodes.

* With PHP Docblock (annotations)
* Or with PHP 8.0 Attributes

## Requirements

- PHP 7.1 or greater (tested on PHP 7.4, 8.0 and 8.1)

## Install

Via Composer

```bash
$ composer require arnaud-ritti/wp-hook
```

## Usage

To automatically wire up your class, simply call the `HookRegistry::bootstrap` method, like so: 

```php
<?php

namespace My\CoolNamespace;

use Ari\WpHook\HookRegistry;
use Ari\WpHook\Models\Action;

class MyClass
{
    public function __construct(HookRegistry $hookRegistry) 
    {
        $hookRegistry->bootstrap($this);
    }
    
    /**
     * @Action(tag="init")    
     */
    #[Action(tag: "init")]
    public function doSomething()
    {
        // do something
    }
}
```

And you're done!

The following annotations can be used in PHP 7:

```php
/**
 * @Action(tag="the hook name", priority=1, accepted_args=1)
 * @Filter(tag="the filter name", priority=1, accepted_args=1)
 * @Shortcode(tag="the shortcode code")
 */
```

For PHP 8, please use attributes:

```php
#[Action(tag: "the hook name", priority: 1, accepted_args: 1)]
#[Filter(tag: "the filter name", priority: 1, accepted_args: 1)]
#[Shortcode(tag: "the shortcode code", priority: 1, accepted_args: 1)]
```

## Testing

```bash
$ composer test
```

## License
WP Hook is released under [the MIT License](LICENSE).
