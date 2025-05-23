<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Icons\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\JsonMockResponse;
use Symfony\UX\Icons\Exception\IconNotFoundException;
use Symfony\UX\Icons\Icon;
use Symfony\UX\Icons\Iconify;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
class IconifyTest extends TestCase
{
    public function testFetchIcon(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
                new JsonMockResponse([
                    'icons' => [
                        'heart' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                            'height' => 24,
                        ],
                    ],
                ]),
            ]),
        );

        $icon = $iconify->fetchIcon('bi', 'heart');

        $this->assertEquals($icon->getInnerSvg(), '<path d="M0 0h24v24H0z" fill="none"/>');
        $this->assertEquals($icon->getAttributes(), ['viewBox' => '0 0 24 24', 'xmlns' => 'https://www.w3.org/2000/svg']);
    }

    public function testFetchIconByAlias(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
                new JsonMockResponse([
                    'aliases' => [
                        'foo' => [
                            'parent' => 'bar',
                        ],
                    ],
                    'icons' => [
                        'bar' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                            'height' => 24,
                        ],
                    ],
                ]),
            ]),
        );

        $icon = $iconify->fetchIcon('bi', 'foo');

        $this->assertEquals($icon->getInnerSvg(), '<path d="M0 0h24v24H0z" fill="none"/>');
        $this->assertEquals($icon->getAttributes(), ['viewBox' => '0 0 24 24', 'xmlns' => 'https://www.w3.org/2000/svg']);
    }

    public function testFetchIconThrowsWhenIconSetDoesNotExists(): void
    {
        $iconify = new Iconify(new NullAdapter(), 'https://example.com', new MockHttpClient(new JsonMockResponse([])));

        $this->expectException(IconNotFoundException::class);
        $this->expectExceptionMessage('The icon "bi:heart" does not exist on iconify.design.');

        $iconify->fetchIcon('bi', 'heart');
    }

    public function testFetchIconUsesIconsetViewBoxHeight(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [
                        'height' => 17,
                    ],
                ]),
                new JsonMockResponse([
                    'icons' => [
                        'heart' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                        ],
                    ],
                ]),
            ]),
        );

        $icon = $iconify->fetchIcon('bi', 'heart');

        $this->assertIsArray($icon->getAttributes());
        $this->assertArrayHasKey('viewBox', $icon->getAttributes());
        $this->assertEquals('0 0 17 17', $icon->getAttributes()['viewBox']);
    }

    public function testFetchIconThrowsWhenViewBoxCannotBeComputed(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
                new JsonMockResponse([
                    'icons' => [
                        'heart' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                        ],
                    ],
                ]),
            ]),
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The icon "bi:heart" does not have a width or height.');

        $iconify->fetchIcon('bi', 'heart');
    }

    public function testFetchIconThrowsWhenStatusCodeNot200(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
                new JsonMockResponse([], ['http_code' => 404]),
            ]),
        );

        $this->expectException(IconNotFoundException::class);
        $this->expectExceptionMessage('The icon "bi:heart" does not exist on iconify.design.');

        $iconify->fetchIcon('bi', 'heart');
    }

    public function testFetchIcons(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
                new JsonMockResponse([
                    'icons' => [
                        'heart' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                            'height' => 17,
                        ],
                        'bar' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                            'height' => 17,
                        ],
                    ],
                ]),
            ]),
        );

        $icons = $iconify->fetchIcons('bi', ['heart', 'bar']);

        $this->assertCount(2, $icons);
        $this->assertSame(['bar', 'heart'], array_keys($icons));
        $this->assertContainsOnlyInstancesOf(Icon::class, $icons);
    }

    public function testFetchIconsByAliases(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'mdi' => [],
                ]),
                new JsonMockResponse([
                    'aliases' => [
                        'capsule' => [
                            'parent' => 'pill',
                        ],
                        'sign' => [
                            'parent' => 'draw',
                        ],
                    ],
                    'icons' => [
                        'pill' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                        ],
                        'glasses' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                        ],
                        'draw' => [
                            'body' => '<path d="M0 0h24v24H0z" fill="none"/>',
                        ],
                    ],
                ]),
            ]),
        );

        $icons = $iconify->fetchIcons('mdi', ['capsule', 'sign', 'glasses']);

        $this->assertCount(3, $icons);
        $this->assertSame(['capsule', 'glasses', 'sign'], array_keys($icons));
        $this->assertContainsOnlyInstancesOf(Icon::class, $icons);
    }

    public function testFetchIconsThrowsWithInvalidIconNames(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
            ]),
        );

        $this->expectException(\InvalidArgumentException::class);

        $iconify->fetchIcons('bi', ['à', 'foo']);
    }

    public function testFetchIconsThrowsWithTooManyIcons(): void
    {
        $iconify = new Iconify(
            cache: new NullAdapter(),
            endpoint: 'https://example.com',
            httpClient: new MockHttpClient([
                new JsonMockResponse([
                    'bi' => [],
                ]),
            ]),
        );

        $this->expectException(\InvalidArgumentException::class);

        $iconify->fetchIcons('bi', array_fill(0, 50, '1234567890'));
    }

    public function testGetMetadata(): void
    {
        $responseFile = __DIR__.'/../Fixtures/Iconify/collections.json';
        $client = $this->createHttpClient(json_decode(file_get_contents($responseFile)));
        $iconify = new Iconify(new NullAdapter(), 'https://localhost', $client);

        $metadata = $iconify->metadataFor('fa6-solid');
        $this->assertSame('Font Awesome Solid', $metadata['name']);
    }

    /**
     * @dataProvider provideChunkCases
     */
    public function testChunk(int $maxQueryLength, string $prefix, array $names, array $chunks): void
    {
        $iconify = new Iconify(
            new NullAdapter(),
            'https://example.com',
            new MockHttpClient([]),
            $maxQueryLength,
        );

        $this->assertSame($chunks, iterator_to_array($iconify->chunk($prefix, $names)));
    }

    public static function provideChunkCases(): iterable
    {
        yield 'no icon should make no chunk' => [
            10,
            'ppppp',
            [],
            [],
        ];

        yield 'one icon should make one chunk' => [
            10,
            'ppppp',
            ['aaaa1'],
            [['aaaa1']],
        ];

        yield 'two icons that should make two chunck' => [
            10,
            'ppppp',
            ['aa1', 'aa2'],
            [['aa1'], ['aa2']],
        ];

        yield 'three icons that should make two chunck' => [
            15,
            'ppppp',
            ['aaa1', 'aaa2', 'aaa3'],
            [['aaa1', 'aaa2'], ['aaa3']],
        ];

        yield 'four icons that should make two chunck' => [
            15,
            'ppppp',
            ['aaaaaaaa1', 'a2', 'a3', 'a4'],
            [['aaaaaaaa1'], ['a2', 'a3', 'a4']],
        ];
    }

    public function testChunkThrowWithIconPrefixTooLong(): void
    {
        $iconify = new Iconify(new NullAdapter(), 'https://example.com', new MockHttpClient([]));

        $prefix = str_pad('p', 101, 'p');
        $name = 'icon';

        $this->expectExceptionMessage(\sprintf('The icon prefix "%s" is too long.', $prefix));

        // We need to iterate over the iterator to trigger the exception
        $result = iterator_to_array($iconify->chunk($prefix, [$name]));
    }

    public function testChunkThrowWithIconNameTooLong(): void
    {
        $iconify = new Iconify(new NullAdapter(), 'https://example.com', new MockHttpClient([]));

        $prefix = 'prefix';
        $name = str_pad('n', 101, 'n');

        $this->expectExceptionMessage(\sprintf('The icon name "%s" is too long.', $name));

        // We need to iterate over the iterator to trigger the exception
        $result = iterator_to_array($iconify->chunk($prefix, [$name]));
    }

    private function createHttpClient(mixed $data, int $code = 200): MockHttpClient
    {
        $mockResponse = new JsonMockResponse($data, ['http_code' => $code]);

        return new MockHttpClient($mockResponse);
    }
}
