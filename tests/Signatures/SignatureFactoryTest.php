<?php

declare(strict_types=1);

namespace League\Glide\Signatures;

use PHPUnit\Framework\TestCase;

class SignatureFactoryTest extends TestCase
{
    public function testCreate()
    {
        $this->assertInstanceOf(Signature::class, SignatureFactory::create('example'));
    }
}
