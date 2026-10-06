<?php

declare(strict_types=1);

use Juaniquillo\MjmlBackendComponents\Builders\MjmlComponentBuilder;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponent;

it('creates any component through the generic make path', function () {
    expect(MjmlComponentBuilder::make(MjmlComponentEnum::COLUMN))->toBeInstanceOf(MjmlBackendComponent::class)
        ->and(MjmlComponentBuilder::make(MjmlComponentEnum::COLUMN)->toHtml())->toBe('<mj-column></mj-column>');
});

it('builds shorthand components', function () {
    expect(MjmlComponentBuilder::head()->toHtml())->toBe('<mj-head></mj-head>')
        ->and(MjmlComponentBuilder::preview('Hello')->toHtml())->toBe('<mj-preview>Hello</mj-preview>')
        ->and(MjmlComponentBuilder::attributes()->toHtml())->toBe('<mj-attributes></mj-attributes>')
        ->and(MjmlComponentBuilder::font('Roboto', 'https://fonts.example/roboto')->toHtml())
        ->toBe('<mj-font name="Roboto" href="https://fonts.example/roboto"></mj-font>')
        ->and(MjmlComponentBuilder::style('.a{color:red}')->toHtml())->toBe('<mj-style>.a{color:red}</mj-style>')
        ->and(MjmlComponentBuilder::style('.a{color:red}', inline: true)->toHtml())
        ->toBe('<mj-style inline="inline">.a{color:red}</mj-style>');
});
