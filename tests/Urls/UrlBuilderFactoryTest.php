<?php

declare(strict_types=1);

namespace League\Glide\Urls;

use PHPUnit\Framework\TestCase;

class UrlBuilderFactoryTest extends TestCase
{
    public function testCreate()
    {
        $urlBuilder = UrlBuilderFactory::create('/img');

        $this->assertInstanceOf(UrlBuilder::class, $urlBuilder);
        $this->assertEquals('/img/image.jpg', $urlBuilder->getUrl('image.jpg'));
    }

    public function testCreateWithSignKey()
    {
        $urlBuilder = UrlBuilderFactory::create('img', 'example-sign-key');

        $this->assertEquals(
            '/img/image.jpg?s=56020c3dc5f68487c14510343c3e2c43',
            $urlBuilder->getUrl('image.jpg'),
        );
    }

    public function testCreateWithSignKeyWithLeadingSlash()
    {
        $urlBuilder = UrlBuilderFactory::create('/img', 'example-sign-key');

        $this->assertEquals(
            '/img/image.jpg?s=56020c3dc5f68487c14510343c3e2c43',
            $urlBuilder->getUrl('image.jpg'),
        );
    }
}
