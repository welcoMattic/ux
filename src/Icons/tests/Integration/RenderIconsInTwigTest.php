<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Icons\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RenderIconsInTwigTest extends KernelTestCase
{
    public function testRenderIcons(): void
    {
        $output = self::getContainer()->get(Environment::class)->render('template1.html.twig');

        $this->assertSame(
            <<<HTML
            <ul class="svg">
                <li id="first"><svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8" aria-hidden="true"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"></path></svg></li>
                <li id="second"><svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-label="AriaLabel"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"></path></svg></li>
                <li id="third"><svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" data-action="string &quot;with&quot; quotes" aria-hidden="true"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"></path></svg></li>
                <li id="fourth"><svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" height="24" width="24" aria-hidden="true"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"></path></svg></li>
                <li id="fifth"><svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8" aria-hidden="true"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"></path></svg></li>
                <li id="sixth"><svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-labelledby="foo"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"></path></svg></li>
                <li id="seventh"><svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"></path></svg></li>
                <li id="eighth"><svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><g fill="none"><path fill="currentColor" d="m12 3l7.794 4.5v7.845a2 2 0 0 1-1 1.732L13 20.423a2 2 0 0 1-2 0l-5.794-3.346a2 2 0 0 1-1-1.732V7.5z" opacity=".16"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3l7.794 4.5v7.845a2 2 0 0 1-1 1.732L13 20.423a2 2 0 0 1-2 0l-5.794-3.346a2 2 0 0 1-1-1.732V7.5z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l-4.33 2.5M12 12l4.33 2.5"/></g></svg></li>
                <li id="ninth"><svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" height="24" width="24" aria-hidden="true"><g fill="none"><path fill="currentColor" d="m12 3l7.794 4.5v7.845a2 2 0 0 1-1 1.732L13 20.423a2 2 0 0 1-2 0l-5.794-3.346a2 2 0 0 1-1-1.732V7.5z" opacity=".16"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3l7.794 4.5v7.845a2 2 0 0 1-1 1.732L13 20.423a2 2 0 0 1-2 0l-5.794-3.346a2 2 0 0 1-1-1.732V7.5z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l-4.33 2.5M12 12l4.33 2.5"/></g></svg></li>
            </ul>
            HTML,
            trim($output)
        );
    }

    public function testRenderAliasIcons(): void
    {
        $templateIcon = '<twig:ux:icon name="flowbite:close-outline" />';
        $outputIcon = self::getContainer()->get(Environment::class)->createTemplate($templateIcon)->render();

        $templateAlias = '<twig:ux:icon name="flowbite:x-outline" />';
        $outputAlias = self::getContainer()->get(Environment::class)->createTemplate($templateAlias)->render();

        $expected = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L17.94 6M18 18L6.06 6"/></svg>';
        $this->assertSame($outputIcon, $expected);
        $this->assertSame($outputIcon, $outputAlias);
    }
}
