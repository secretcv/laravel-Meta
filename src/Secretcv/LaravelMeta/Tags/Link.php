<?php

namespace Secretcv\LaravelMeta\Tags;

class Link extends TagAbstract
{
    protected static $available = ['alternate', 'canonical', 'next', 'prev'];

    public static function tagDefault($key, $value)
    {
        if (in_array($key, self::$available, true)) {
            return '<link rel="' . $key . '" href="' . $value . '" />';
        }
    }
}
