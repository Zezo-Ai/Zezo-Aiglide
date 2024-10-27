<?php

declare(strict_types=1);

namespace League\Glide\Manipulators;

use Intervention\Image\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

class GammaTest extends TestCase
{
    private $manipulator;

    public function setUp(): void
    {
        $this->manipulator = new Gamma();
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('League\Glide\Manipulators\Gamma', $this->manipulator);
    }

    public function testRun()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('gamma')->with('1.5')->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->setParams(['gam' => '1.5'])->run($image)
        );
    }

    public function testGetGamma()
    {
        $this->assertSame(1.5, $this->manipulator->setParams(['gam' => '1.5'])->getGamma());
        $this->assertSame(1.5, $this->manipulator->setParams(['gam' => 1.5])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => null])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => 'a'])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => '.1'])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => '9.999'])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => '0.005'])->getGamma());
        $this->assertSame(null, $this->manipulator->setParams(['gam' => '-1'])->getGamma());
    }
}
