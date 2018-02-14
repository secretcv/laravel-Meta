<?php

use Secretcv\LaravelMeta\Meta;

/**
 * Class Tests
 */
class Tests extends PHPUnit_Framework_TestCase
{
    /**
     * @var string $title
     */
    protected static $title;
    /**
     * @var Meta $meta
     */
    protected $meta;

    public function setUp()
    {
        self::$title = self::text(20);

        $this->meta = new Meta(
            [
                'title_limit'       => 70,
                'description_limit' => 200,
                'image_limit'       => 5,
            ]
        );
    }

    /**
     * @param      $length
     * @param bool $withSpace
     *
     * @return string
     */
    protected static function text($length, $withSpace = true)
    {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if ($withSpace) {
            $base .= ' ';
        }

        $text = '';

        while (mb_strlen($text) < $length) {
            $text .= str_shuffle($base);
        }

        return mb_substr($text, 0, $length);
    }

    public function testInstance()
    {
        $meta = Meta::getInstance();

        static::assertInstanceOf(Meta::class, $meta);
    }

    public function testMetaTitle()
    {
        $response = $this->meta->set('title', $text = self::text(50));

        static::assertSame($text, $response);

        $response = $this->meta->set('title', $text = self::text(80));

        static::assertNotTrue($text, $response);
        static::assertSame(mb_strlen($response), 70);
    }

    public function testMetaDescription()
    {
        $response = $this->meta->set('description', $text = self::text(50));

        static::assertSame($text, $response);

        $response = $this->meta->set('description', $text = self::text(250));

        static::assertNotSame($text, $response);
        static::assertSame(mb_strlen($response), 200);
    }

    public function testMetaTitleWithTitle()
    {
        $response = $this->meta->title(self::$title);

        static::assertSame(self::$title, $response);
        static::assertSame(self::$title, $this->meta->title());

        $response = $this->meta->set('title', $text = self::text(30));

        static::assertSame($text . ' - ' . self::$title, $response);

        $response = $this->meta->set('title', $text = self::text(80));

        static::assertNotSame($text . ' - ' . self::$title, $response);
        static::assertSame(mb_strlen($response), 70);
    }

    public function testMetaImage()
    {
        $response = $this->meta->set('image', $text = self::text(30, false));

        static::assertSame($text, $response);

        $response = $this->meta->set('image', $text = self::text(150, false));

        static::assertSame($text, $response);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->meta->set('image', $text = self::text(80, false));

            if ($i > 2) {
                static::assertNull($response);
            } else {
                static::assertSame($text, $response);
            }
        }

        static::assertSame(count($this->meta->get('image')), 5);
    }

    public function testTagTitle()
    {
        $this->meta->title(self::$title);
        $this->meta->set('title', $text = self::text(20));

        $tag = $this->meta->tag('title');

        static::assertSame(mb_substr_count($tag, '<meta name="title"'), 1);
        static::assertSame(mb_substr_count($tag, '<title>'), 1);
        static::assertTrue(mb_strstr($tag, self::$title) ? true : false);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testTwitterTitle()
    {
        $this->meta->title(self::$title);
        $this->meta->set('twitter:title', $text = self::text(20));

        $tag = $this->meta->tag('twitter:title');

        static::assertSame(mb_substr_count($tag, '<meta name="twitter:title"'), 1);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testOgTitle()
    {
        $this->meta->title(self::$title);
        $this->meta->set('og:title', $text = self::text(20));

        $tag = $this->meta->tag('og:title');

        static::assertSame(mb_substr_count($tag, '<meta property="og:title"'), 1);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testTagDescription()
    {
        $this->meta->set('description', $text = self::text(150));

        $tag = $this->meta->tag('description');

        static::assertSame(mb_substr_count($tag, '<meta name="description"'), 1);
        static::assertSame(mb_substr_count($tag, '<description>'), 0);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testTwitterDescription()
    {
        $this->meta->set('twitter:description', $text = self::text(150));

        $tag = $this->meta->tag('twitter:description');

        static::assertSame(mb_substr_count($tag, '<meta name="twitter:description"'), 1);
        static::assertSame(mb_substr_count($tag, '<description>'), 0);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testOgDescription()
    {
        $this->meta->set('og:description', $text = self::text(150));

        $tag = $this->meta->tag('og:description');

        static::assertSame(mb_substr_count($tag, '<meta property="og:description"'), 1);
        static::assertSame(mb_substr_count($tag, '<description>'), 0);
        static::assertTrue(mb_strstr($tag, $text) ? true : false);
    }

    public function testTagImage()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->meta->set('image', self::text(80, false));
        }

        $tag = $this->meta->tag('image', 'defaultTest');

        static::assertSame(mb_substr_count($tag, '<image>'), 0);
        static::assertSame(mb_substr_count($tag, '<link rel="image_src"'), 5);
    }

    public function testTwitterImage()
    {
        $this->meta->set('twitter:image', self::text(80, false));

        $tag = $this->meta->tag('twitter:image');

        static::assertSame(mb_substr_count($tag, '<meta name="twitter:image"'), 1);
        static::assertSame(mb_substr_count($tag, '<image>'), 0);
    }

    public function testOgImage()
    {
        $this->meta->set('og:image', self::text(80, false));

        $tag = $this->meta->tag('og:image');

        static::assertSame(mb_substr_count($tag, '<meta property="og:image"'), 1);
        static::assertSame(mb_substr_count($tag, '<image>'), 0);
    }

    public function testRelLinks()
    {
        $this->meta->set('next', self::text(80, false));
        $this->meta->set('prev', self::text(80, false));
        $this->meta->set('canonical', self::text(80, false));
        $this->meta->set('alternate', self::text(80, false));

        $tags = $this->meta->tags();

        static::assertSame(mb_substr_count($tags, '<link rel="next"'), 1);
        static::assertSame(mb_substr_count($tags, '<link rel="prev"'), 1);
        static::assertSame(mb_substr_count($tags, '<link rel="canonical"'), 1);
        static::assertSame(mb_substr_count($tags, '<link rel="alternate"'), 1);
    }

    public function testDatePosted()
    {
        $this->meta->set('datePosted', self::text(80));

        static::assertSame(mb_substr_count($this->meta->tags(), '<meta itemprop="datePosted"'), 1);
    }

    public function testEmptyValue()
    {
        $this->meta->set('emptyValue', '');

        static::assertEmpty($this->meta->tags());
    }
}
