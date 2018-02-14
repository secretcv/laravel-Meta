<?php

namespace Secretcv\LaravelMeta\Tags;

class MetaName extends TagAbstract
{
    protected static $available = ['title', 'description'];

    public static function tagDefault($key, $value)
    {
        if (in_array($key, self::$available, true)) {
            return '<meta name="' . $key . '" content="' . $value . '" />';
        }
    }
}
