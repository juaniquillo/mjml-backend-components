<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\MjmlBackendComponents\Builders\MjmlComponentBuilder;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponent;

it('renders the root element as plain mjml', function () {
    $html = MjmlComponentBuilder::document()->toHtml();

    expect($html)->toBe('<mjml></mjml>');
});

it('renders body elements with the mj- prefix', function () {
    $html = MjmlComponentBuilder::make(MjmlComponentEnum::SECTION)->toHtml();

    expect($html)->toBe('<mj-section></mj-section>');
});

it('renders attributes into the tag', function () {
    $html = MjmlComponentBuilder::text('Hi', ['color' => 'red'])->toHtml();

    expect($html)->toBe('<mj-text color="red">Hi</mj-text>');
});

it('escapes attribute values for xml', function () {
    $html = MjmlComponentBuilder::text('x', ['title' => 'say "hi" & <bye>'])->toHtml();

    expect($html)->toBe('<mj-text title="say &quot;hi&quot; &amp; &lt;bye&gt;">x</mj-text>');
});

it('renders nested components recursively', function () {
    $html = MjmlComponentBuilder::document([
        MjmlComponentBuilder::head([
            MjmlComponentBuilder::preview('Hello'),
        ]),
        MjmlComponentBuilder::make(MjmlComponentEnum::BODY)->setContents([
            MjmlComponentBuilder::section(
                [MjmlComponentBuilder::text('Hi', ['color' => 'red'])],
                ['background-color' => '#fff'],
            ),
        ]),
    ])->toHtml();

    expect($html)->toBe(
        '<mjml>'.
        '<mj-head><mj-preview>Hello</mj-preview></mj-head>'.
        '<mj-body><mj-section background-color="#fff"><mj-text color="red">Hi</mj-text></mj-section></mj-body>'.
        '</mjml>',
    );
});

it('serializes to an array', function () {
    $array = MjmlComponentBuilder::text('Hi', ['color' => 'red'])->toArray();

    expect($array['name'])->toBe('text')
        ->and($array['component'])->toBe(MjmlBackendComponent::class)
        ->and($array['attributes'])->toBe(['color' => 'red'])
        ->and($array['contents'])->toBeArray();
});

it('exposes an attribute bag', function () {
    $bag = MjmlComponentBuilder::text('Hi', ['color' => 'red'])->getAttributeBag();

    expect($bag)->toBeInstanceOf(DefaultAttributeBag::class)
        ->and($bag->getAttributes())->toBe(['color' => 'red']);
});
