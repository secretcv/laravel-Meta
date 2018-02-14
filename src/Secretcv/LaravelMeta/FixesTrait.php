<?php

namespace Secretcv\LaravelMeta;

trait FixesTrait
{
    /**
     * @param  string $text
     *
     * @return string
     */
    private function plain($text)
    {
        return str_replace('"', '&quot;', preg_replace('/[\r\n\s]+/', ' ', strip_tags($text)));
    }

    /**
     * @param  string $text
     * @param  string $key
     *
     * @return string
     */
    private function cut($text, $key)
    {
        if (empty($text) || !is_string($text)) {
            return $text;
        }

        if (is_int($key)) {
            $limit = $key;
        } elseif (is_string($key) && !empty($this->config[$key . '_limit'])) {
            $limit = $this->config[$key . '_limit'];
        } else {
            return $text;
        }

        $length = mb_strlen($text);

        if ($length <= $limit) {
            return $text;
        }

        $text = mb_substr($text, 0, ($limit -= 3));

        return $text . '...';
    }
}
