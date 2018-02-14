<?php

namespace Secretcv\LaravelMeta\Tags;

class Meta extends TagAbstract
{
    protected static $available = ['datePosted'];

    public static function tagDefault($key, $value)
    {
        if (in_array($key, self::$available, true)) {
            return '<meta itemprop="' . $key . '" content="' . $value . '" />';
        }
    }
}
