<?php

declare(strict_types=1);

use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use Juaniquillo\MjmlBackendComponents\Factories\MjmlComponentFactory;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponent;

it('creates any component through the generic make path', function () {
    expect(MjmlComponentFactory::make(MjmlComponentEnum::COLUMN))->toBeInstanceOf(MjmlBackendComponent::class)
        ->and(MjmlComponentFactory::make(MjmlComponentEnum::COLUMN)->toHtml())->toBe('<mj-column></mj-column>');
});

it('creates shorthand components', function () {
    expect(MjmlComponentFactory::head()->toHtml())->toBe('<mj-head></mj-head>')
        ->and(MjmlComponentFactory::preview('Hello')->toHtml())->toBe('<mj-preview>Hello</mj-preview>')
        ->and(MjmlComponentFactory::attributes()->toHtml())->toBe('<mj-attributes></mj-attributes>')
        ->and(MjmlComponentFactory::font('Roboto', 'https://fonts.example/roboto')->toHtml())
        ->toBe('<mj-font name="Roboto" href="https://fonts.example/roboto"></mj-font>')
        ->and(MjmlComponentFactory::style('.a{color:red}')->toHtml())->toBe('<mj-style>.a{color:red}</mj-style>')
        ->and(MjmlComponentFactory::style('.a{color:red}', inline: true)->toHtml())
        ->toBe('<mj-style inline="inline">.a{color:red}</mj-style>');
});
