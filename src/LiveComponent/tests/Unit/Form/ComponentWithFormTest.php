<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\LiveComponent\Tests\Unit\Form;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\LiveComponent\Tests\Fixtures\Component\FormComponentWithManyDifferentFieldsType;
use Symfony\UX\LiveComponent\Tests\Fixtures\Factory\CategoryFixtureEntityFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @author Jakub Caban <kuba.iluvatar@gmail.com>
 */
class ComponentWithFormTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    public function testFormValues(): void
    {
        $category = CategoryFixtureEntityFactory::createMany(5);
        $id = $category[0]->getId();

        $formFactory = self::getContainer()->get('form.factory');
        $component = new FormComponentWithManyDifferentFieldsType($formFactory);
        $component->initialData = [
            'choice_multiple' => [2],
            'select_multiple' => [2],
            'checkbox_checked' => true,
        ];
        $component->initializeForm([]);

        $this->assertSame(
            [
                'text' => '',
                'textarea' => '',
                'range' => '',
                'choice' => '',
                'choice_required_with_placeholder' => '',
                'choice_required_with_empty_placeholder' => '',
                'choice_required_without_placeholder' => '2',
                'choice_required_without_placeholder_and_choice_group' => 'ok',
                'choice_required_with_preferred_choices_array' => 'foo_value',
                'choice_required_with_preferred_choices_callback' => '1',
                'choice_required_with_empty_preferred_choices' => 'ok',
                'choice_expanded' => '',
                'choice_multiple' => ['2'],
                'select_multiple' => ['2'],
                'entity' => (string) $id,
                'checkbox' => null,
                'checkbox_checked' => '1',
                'file' => '',
                'hidden' => '',
                'complexType' => [
                    'sub_field' => '',
                ],
            ],
            $component->formValues
        );
    }
}
