<?php

namespace App\Tests\unit\core\src\Service;

use App\Themes\Service\ThemeImageFinder;
use App\Themes\Service\ThemeImageService;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Exception;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class ThemeImageServiceTest
 * @package App\Tests
 */
class ThemeImageServiceTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var ThemeImageService
     */
    protected $themeImageService;

    /**
     * @throws Exception
     */
    protected function _before(): void
    {
        $themeImagePaths = [
            'legacy/themes/default/images',
            'legacy/custom/themes/default/images',
            'dist/themes/default/images',
            'legacy/themes/<theme>/images',
            'legacy/custom/themes/<theme>/images',
            'dist/themes/<theme>/images',
        ];
        $themeImageSupportedTypes = [
            'svg',
            'png',
            'jpg',
            'jpeg',
            'gif',
        ];

        $mockImages = [
            '/public/legacy/themes/default/images' => [
                new SplFileInfo('logo.png', 'legacy/themes/default/images', 'legacy/themes/default/images'),
                new SplFileInfo('legacy_image.png', 'legacy/themes/default/images', 'legacy/themes/default/images'),
                new SplFileInfo('to_be_overwritten.png', 'legacy/themes/default/images',
                    'legacy/themes/default/images'),
                new SplFileInfo('to_be_overwritten_with_different_extension.png', 'legacy/themes/default/images',
                    'legacy/themes/default/images')
            ],
            '/public/legacy/themes/suite8/images' => [
                new SplFileInfo('to_be_overwritten.png', 'legacy/themes/suite8/images', 'legacy/themes/suite8/images'),
                new SplFileInfo('to_be_overwritten_with_different_extension.svg', 'legacy/themes/suite8/images',
                    'legacy/themes/suite8/images')
            ],
            '/public/dist/themes/suite8/images' => [
                new SplFileInfo('logo.png', 'dist/themes/suite8/images', 'dist/themes/suite8/images'),
            ]
        ];

        /** @var ThemeImageFinder $themeImageFinder */
        $themeImageFinder = $this->make(
            ThemeImageFinder::class,
            [
                'find' => static function ($fullPath) use ($mockImages) {
                    if (empty($mockImages[$fullPath])) {
                        return [];
                    }

                    return $mockImages[$fullPath];
                }
            ]
        );

        $this->themeImageService = new ThemeImageService(
            $themeImagePaths,
            $themeImageSupportedTypes,
            '',
            $themeImageFinder
        );
    }

    /**
     * Ensure the format of the returned items is the expected
     */
    public function testItemFormat(): void
    {
        $images = $this->themeImageService->get('suite8');
        static::assertNotNull($images);
        static::assertNotEmpty($images->getItems());
        static::assertArrayHasKey('logo', $images->getItems());
        $item = $images->getItems()['logo'];
        static::assertNotEmpty($item);

        static::assertCount(3, $item);
        static::assertArrayHasKey('path', $item);
        static::assertArrayHasKey('name', $item);
        static::assertArrayHasKey('type', $item);
        static::assertEquals('logo', $item['name']);
    }

    /**
     * Test image override order
     */
    public function testImageOverrides(): void
    {
        $expected = [
            'logo' => [
                'path' => 'dist/themes/suite8/images/logo.png',
                'name' => 'logo',
                'type' => 'png'
            ],
            'legacy_image' => [
                'path' => 'legacy/themes/default/images/legacy_image.png',
                'name' => 'legacy_image',
                'type' => 'png'
            ],
            'to_be_overwritten_with_different_extension' => [
                'path' => 'legacy/themes/suite8/images/to_be_overwritten_with_different_extension.svg',
                'name' => 'to_be_overwritten_with_different_extension',
                'type' => 'svg'
            ],
            'to_be_overwritten' => [
                'path' => 'legacy/themes/suite8/images/to_be_overwritten.png',
                'name' => 'to_be_overwritten',
                'type' => 'png'
            ],
        ];

        $images = $this->themeImageService->get('suite8');
        static::assertNotNull($images);
        static::assertNotEmpty($images->getItems());

        static::assertEquals($images->getItems(), $expected);
    }
}
