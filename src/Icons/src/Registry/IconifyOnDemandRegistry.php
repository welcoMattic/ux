<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Icons\Registry;

use Symfony\UX\Icons\Exception\HttpClientNotInstalledException;
use Symfony\UX\Icons\Exception\IconNotFoundException;
use Symfony\UX\Icons\Icon;
use Symfony\UX\Icons\Iconify;
use Symfony\UX\Icons\IconRegistryInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class IconifyOnDemandRegistry implements IconRegistryInterface
{
    public function __construct(
        private Iconify $iconify,
        private ?array $prefixAliases = [],
    ) {
    }

    public function get(string $name): Icon
    {
        if (2 !== \count($parts = explode(':', $name))) {
            throw new IconNotFoundException(\sprintf('The icon name "%s" is not valid.', $name));
        }
        [$prefix, $icon] = $parts;

        try {
            return $this->iconify->fetchIcon($this->prefixAliases[$prefix] ?? $prefix, $icon);
        } catch (HttpClientNotInstalledException $e) {
            throw new IconNotFoundException($e->getMessage());
        }
    }
}
