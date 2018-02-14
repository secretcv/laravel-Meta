<?php

namespace Secretcv\LaravelMeta\Tags;

class MetaProperty extends TagAbstract
{
    protected static $available
        = [
            'og:title',
            'og:type',
            'og:image',
            'og:url',
            'og:audio',
            'og:description',
            'og:determiner',
            'og:locale',
            'og:site_name',
            'og:video',
        ];

    public static function tagDefault($key, $value)
    {
        if (in_array($key, self::$available, true)) {
            return '<meta property="' . $key . '" content="' . $value . '" />';
        }
    }
}
