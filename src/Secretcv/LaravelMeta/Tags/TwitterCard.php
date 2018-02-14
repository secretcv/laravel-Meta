<?php

namespace Secretcv\LaravelMeta\Tags;

class TwitterCard extends TagAbstract
{
    protected static $available
        = [
            'twitter:card',
            'twitter:site',
            'twitter:site:id',
            'twitter:creator',
            'twitter:creator:id',
            'twitter:description',
            'twitter:title',
            'twitter:image',
            'twitter:image:alt',
            'twitter:player',
            'twitter:player:width',
            'twitter:player:height',
            'twitter:player:stream',
            'twitter:app:name:iphone',
            'twitter:app:id:iphone',
            'twitter:app:url:iphone',
            'twitter:app:name:ipad',
            'twitter:app:id:ipad',
            'twitter:app:url:ipad',
            'twitter:app:name:googleplay',
            'twitter:app:id:googleplay',
            'twitter:app:url:googleplay',
        ];

    public static function tagDefault($key, $value)
    {
        if (in_array($key, self::$available, true)) {
            return '<meta name="' . $key . '" content="' . $value . '" />';
        }
    }
}
