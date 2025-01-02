<?php

declare(strict_types=1);

namespace League\Glide\Manipulators;

use Intervention\Image\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    private Size $manipulator;

    public function setUp(): void
    {
        $this->manipulator = new Size();
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Size::class, $this->manipulator);
    }

    public function testSetMaxImageSize()
    {
        $this->manipulator->setMaxImageSize(500 * 500);
        $this->assertSame(500 * 500, $this->manipulator->getMaxImageSize());
    }

    public function testGetMaxImageSize()
    {
        $this->assertNull($this->manipulator->getMaxImageSize());
    }

    public function testRun()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn('200')->twice();
            $mock->shouldReceive('height')->andReturn('200')->once();
            $mock->shouldReceive('scale')->with(100, 100)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->setParams(['w' => 100])->run($image)
        );
    }

    public function testGetWidth()
    {
        $this->assertSame(100, $this->manipulator->setParams(['w' => 100])->getWidth());
        $this->assertSame(100, $this->manipulator->setParams(['w' => 100.1])->getWidth());
        $this->assertSame(null, $this->manipulator->setParams(['w' => null])->getWidth());
        $this->assertSame(null, $this->manipulator->setParams(['w' => 'a'])->getWidth());
        $this->assertSame(null, $this->manipulator->setParams(['w' => '-100'])->getWidth());
    }

    public function testGetHeight()
    {
        $this->assertSame(100, $this->manipulator->setParams(['h' => 100])->getHeight());
        $this->assertSame(100, $this->manipulator->setParams(['h' => 100.1])->getHeight());
        $this->assertSame(null, $this->manipulator->setParams(['h' => null])->getHeight());
        $this->assertSame(null, $this->manipulator->setParams(['h' => 'a'])->getHeight());
        $this->assertSame(null, $this->manipulator->setParams(['h' => '-100'])->getHeight());
    }

    public function testGetFit()
    {
        $this->assertSame('contain', $this->manipulator->setParams(['fit' => 'contain'])->getFit());
        $this->assertSame('fill', $this->manipulator->setParams(['fit' => 'fill'])->getFit());
        $this->assertSame('fill-max', $this->manipulator->setParams(['fit' => 'fill-max'])->getFit());
        $this->assertSame('max', $this->manipulator->setParams(['fit' => 'max'])->getFit());
        $this->assertSame('stretch', $this->manipulator->setParams(['fit' => 'stretch'])->getFit());
        $this->assertSame('cover', $this->manipulator->setParams(['fit' => 'cover'])->getFit());
        $this->assertSame('cover', $this->manipulator->setParams(['fit' => 'crop'])->getFit());
        $this->assertSame('cover', $this->manipulator->setParams(['fit' => 'crop-top-left'])->getFit());
        $this->assertSame('cover', $this->manipulator->setParams(['fit' => 'crop-center'])->getFit());
        $this->assertSame('crop', $this->manipulator->setParams(['fit' => 'crop-27-75'])->getFit());
        $this->assertSame('contain', $this->manipulator->setParams(['fit' => 'invalid'])->getFit());
    }

    public function testGetCrop()
    {
        $this->assertSame([0, 0, 1.0], $this->manipulator->setParams(['fit' => 'crop-top-left'])->getCrop());
        $this->assertSame([0, 100, 1.0], $this->manipulator->setParams(['fit' => 'crop-bottom-left'])->getCrop());
        $this->assertSame([0, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-left'])->getCrop());
        $this->assertSame([100, 0, 1.0], $this->manipulator->setParams(['fit' => 'crop-top-right'])->getCrop());
        $this->assertSame([100, 100, 1.0], $this->manipulator->setParams(['fit' => 'crop-bottom-right'])->getCrop());
        $this->assertSame([100, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-right'])->getCrop());
        $this->assertSame([50, 0, 1.0], $this->manipulator->setParams(['fit' => 'crop-top'])->getCrop());
        $this->assertSame([50, 100, 1.0], $this->manipulator->setParams(['fit' => 'crop-bottom'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-center'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-center'])->getCrop());
        $this->assertSame([25, 75, 1.0], $this->manipulator->setParams(['fit' => 'crop-25-75'])->getCrop());
        $this->assertSame([0, 100, 1.0], $this->manipulator->setParams(['fit' => 'crop-0-100'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-101-102'])->getCrop());
        $this->assertSame([25, 75, 1.0], $this->manipulator->setParams(['fit' => 'crop-25-75-1'])->getCrop());
        $this->assertSame([25, 75, 1.5], $this->manipulator->setParams(['fit' => 'crop-25-75-1.5'])->getCrop());
        $this->assertSame([25, 75, 1.555], $this->manipulator->setParams(['fit' => 'crop-25-75-1.555'])->getCrop());
        $this->assertSame([25, 75, 2.0], $this->manipulator->setParams(['fit' => 'crop-25-75-2'])->getCrop());
        $this->assertSame([25, 75, 100.0], $this->manipulator->setParams(['fit' => 'crop-25-75-100'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'crop-25-75-101'])->getCrop());
        $this->assertSame([50, 50, 1.0], $this->manipulator->setParams(['fit' => 'invalid'])->getCrop());
    }

    public function testGetDpr()
    {
        $this->assertSame(1.0, $this->manipulator->setParams(['dpr' => 'invalid'])->getDpr());
        $this->assertSame(1.0, $this->manipulator->setParams(['dpr' => '-1'])->getDpr());
        $this->assertSame(1.0, $this->manipulator->setParams(['dpr' => '9'])->getDpr());
        $this->assertSame(2.0, $this->manipulator->setParams(['dpr' => '2'])->getDpr());
    }

    public function testResolveMissingDimensions()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(400);
            $mock->shouldReceive('height')->andReturn(200);
        });

        $this->assertSame([400, 200], $this->manipulator->resolveMissingDimensions($image, null, null));
        $this->assertSame([100, 50], $this->manipulator->resolveMissingDimensions($image, 100, null));
        $this->assertSame([200, 100], $this->manipulator->resolveMissingDimensions($image, null, 100));
    }

    public function testResolveMissingDimensionsWithOddDimensions()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(1024);
            $mock->shouldReceive('height')->andReturn(553);
        });

        $this->assertSame([411, 222], $this->manipulator->resolveMissingDimensions($image, 411, null));
    }

    public function testLimitImageSize()
    {
        $this->assertSame([1000, 1000], $this->manipulator->limitImageSize(1000, 1000));
        $this->manipulator->setMaxImageSize(500 * 500);
        $this->assertSame([500, 500], $this->manipulator->limitImageSize(500, 500));
        $this->assertSame([500, 500], $this->manipulator->limitImageSize(1000, 1000));
    }

    public function testRunResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100)->times(4);
            $mock->shouldReceive('height')->andReturn(100)->times(4);
            $mock->shouldReceive('crop')->andReturn($mock)->once();
            $mock->shouldReceive('contain')->andReturn($mock)->once();
            $mock->shouldReceive('resize')->with(100, 100)->andReturn($mock)->once();
            $mock->shouldReceive('pad')->andReturn($mock)->once();
            $mock->shouldReceive('scaleDown')->with(100, 100)->andReturn($mock)->times(1);
            $mock->shouldReceive('scale')->with(100, 100)->andReturn($mock)->times(2);
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'contain', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'fill', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'fill-max', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'max', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'stretch', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'crop', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'crop-top-right', 100, 100)
        );

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runResize($image, 'invalid', 100, 100)
        );
    }

    public function testRunContainResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('scale')->with(100, 100)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runContainResize($image, 100, 100)
        );
    }

    public function testRunFillResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('pad')->with(100, 100)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runFillResize($image, 100, 100)
        );
    }

    public function testRunMaxResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('scaleDown')->with(100, 100)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runMaxResize($image, 100, 100)
        );
    }

    public function testRunStretchResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('resize')->with(100, 100)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runStretchResize($image, 100, 100)
        );
    }

    public function testRunCropResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100)->times(4);
            $mock->shouldReceive('height')->andReturn(100)->times(4);
            $mock->shouldReceive('scale')->with(100, 100)->andReturn($mock)->once();
            $mock->shouldReceive('crop')->with(100, 100, 0, 0)->andReturn($mock)->once();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->runCropResize($image, 100, 100)
        );
    }

    public function testRunCoverResize()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100);
            $mock->shouldReceive('height')->andReturn(100);
            $mock->shouldReceive('cover')->with(50, 50, 'center')->andReturn($mock)->once();
        });

        $this->manipulator->setParams(['w' => 50, 'h' => 50, 'fit' => 'crop']);

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->run($image)
        );
    }

    public function testRunCoverResizePosition()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100);
            $mock->shouldReceive('height')->andReturn(100);
            $mock->shouldReceive('cover')->with(50, 50, 'top-left')->andReturn($mock)->once();
        });

        $this->manipulator->setParams(['w' => 50, 'h' => 50, 'fit' => 'crop-top-left']);

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->run($image)
        );
    }

    public function testResizeDoesNotRunWhenNoParamsAreSet()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100)->twice();
            $mock->shouldReceive('height')->andReturn(100)->twice();
            $mock->shouldReceive('resize')->never();
        });

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->run($image)
        );
    }

    public function testResizeDoesNotRunWhenSettingFitCropToCenterWithNoZoom()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100)->twice();
            $mock->shouldReceive('height')->andReturn(100)->twice();
            $mock->shouldReceive('resize')->never();
        });

        $this->manipulator->setParams(['fit' => 'crop-50-50-1']);

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->run($image)
        );
    }

    public function testResizeDoesRunWhenDimensionsAreTheSameAndTheCropZoomIsNotDefaultOne()
    {
        $image = \Mockery::mock(ImageInterface::class, function ($mock) {
            $mock->shouldReceive('width')->andReturn(100);
            $mock->shouldReceive('height')->andReturn(100);
            $mock->shouldReceive('scale')->once();
            $mock->shouldReceive('crop')->once()->andReturn($mock);
        });

        $this->manipulator->setParams(['fit' => 'crop-50-50-3.2']);

        $this->assertInstanceOf(
            ImageInterface::class,
            $this->manipulator->run($image)
        );
    }
}
