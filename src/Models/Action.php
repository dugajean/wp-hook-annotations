<?php

declare(strict_types=1);

namespace WpHookAnnotations\Models;

class Action extends Filter
{
    public function trigger()
    {
        add_action($this->tag, $this->callable, $this->priority, $this->acceptedArgs);
    }
}
