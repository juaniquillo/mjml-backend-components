<?php

declare(strict_types=1);

use Juaniquillo\BaseBackendComponent\Components\DefaultAttributeBag;
use Juaniquillo\MjmlBackendComponents\Compilers\NodeProcessCompiler;
use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use Juaniquillo\MjmlBackendComponents\Contracts\Htmlable;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use Juaniquillo\MjmlBackendComponents\Factories\MjmlComponentFactory;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponent;

it('renders the root element as plain mjml', function () {
    $html = MjmlComponentFactory::document()->toHtml();

    expect($html)->toBe('<mjml></mjml>');
});

it('implements the local htmlable contract', function () {
    expect(MjmlComponentFactory::text('Hi'))->toBeInstanceOf(Htmlable::class);
});

it('renders body elements with the mj- prefix', function () {
    $html = MjmlComponentFactory::make(MjmlComponentEnum::SECTION)->toHtml();

    expect($html)->toBe('<mj-section></mj-section>');
});

it('renders attributes into the tag', function () {
    $html = MjmlComponentFactory::text('Hi', ['color' => 'red'])->toHtml();

    expect($html)->toBe('<mj-text color="red">Hi</mj-text>');
});

it('escapes attribute values for xml', function () {
    $html = MjmlComponentFactory::text('x', ['title' => 'say "hi" & <bye>'])->toHtml();

    expect($html)->toBe('<mj-text title="say &quot;hi&quot; &amp; &lt;bye&gt;">x</mj-text>');
});

it('renders nested components recursively', function () {
    $html = MjmlComponentFactory::document([
        MjmlComponentFactory::head([
            MjmlComponentFactory::preview('Hello'),
        ]),
        MjmlComponentFactory::make(MjmlComponentEnum::BODY)->setContents([
            MjmlComponentFactory::section(
                [MjmlComponentFactory::text('Hi', ['color' => 'red'])],
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
    $array = MjmlComponentFactory::text('Hi', ['color' => 'red'])->toArray();

    expect($array['name'])->toBe('text')
        ->and($array['component'])->toBe(MjmlBackendComponent::class)
        ->and($array['attributes'])->toBe(['color' => 'red'])
        ->and($array['contents'])->toBeArray();
});

it('exposes an attribute bag', function () {
    $bag = MjmlComponentFactory::text('Hi', ['color' => 'red'])->getAttributeBag();

    expect($bag)->toBeInstanceOf(DefaultAttributeBag::class)
        ->and($bag->getAttributes())->toBe(['color' => 'red']);
});

it('renders every enum case with its mjml tag', function (MjmlComponentEnum $component, string $expected) {
    expect(MjmlComponentFactory::make($component)->toHtml())->toBe($expected);
})->with([
    'document root' => [MjmlComponentEnum::MJML, '<mjml></mjml>'],
    'head' => [MjmlComponentEnum::HEAD, '<mj-head></mj-head>'],
    'body' => [MjmlComponentEnum::BODY, '<mj-body></mj-body>'],
    'section' => [MjmlComponentEnum::SECTION, '<mj-section></mj-section>'],
    'column' => [MjmlComponentEnum::COLUMN, '<mj-column></mj-column>'],
    'group' => [MjmlComponentEnum::GROUP, '<mj-group></mj-group>'],
    'text' => [MjmlComponentEnum::TEXT, '<mj-text></mj-text>'],
    'image' => [MjmlComponentEnum::IMAGE, '<mj-image></mj-image>'],
    'button' => [MjmlComponentEnum::BUTTON, '<mj-button></mj-button>'],
    'divider' => [MjmlComponentEnum::DIVIDER, '<mj-divider></mj-divider>'],
    'spacer' => [MjmlComponentEnum::SPACER, '<mj-spacer></mj-spacer>'],
    'hero' => [MjmlComponentEnum::HERO, '<mj-hero></mj-hero>'],
    'raw' => [MjmlComponentEnum::RAW, '<mj-raw></mj-raw>'],
    'attributes' => [MjmlComponentEnum::ATTRIBUTES, '<mj-attributes></mj-attributes>'],
    'style' => [MjmlComponentEnum::STYLE, '<mj-style></mj-style>'],
    'font' => [MjmlComponentEnum::FONT, '<mj-font></mj-font>'],
    'title' => [MjmlComponentEnum::TITLE, '<mj-title></mj-title>'],
    'preview' => [MjmlComponentEnum::PREVIEW, '<mj-preview></mj-preview>'],
]);

it('renders non-string attribute values', function () {
    $html = MjmlComponentFactory::text('Hi', ['font-size' => 14, 'align' => 'center'])->toHtml();

    expect($html)->toBe('<mj-text font-size="14" align="center">Hi</mj-text>');
});

it('serializes nested trees to arrays', function () {
    $array = MjmlComponentFactory::document([
        MjmlComponentFactory::text('Hi'),
    ])->toArray();

    expect($array['name'])->toBe('mjml')
        ->and($array['contents'])->toHaveCount(1)
        ->and($array['contents'][0]['name'])->toBe('text')
        ->and($array['contents'][0]['contents'])->toBe(['Hi']);
});

it('delegates rendering to the given compiler', function () {
    $compiler = new class implements CompilesMjml
    {
        public function compile(string $mjmlMarkup): string
        {
            return $mjmlMarkup;
        }
    };

    $output = MjmlComponentFactory::document([
        MjmlComponentFactory::text('Hello Lindsey!'),
    ])->renderHtml($compiler);

    expect($output)->toContain('<mjml>')
        ->toContain('<mj-text>Hello Lindsey!</mj-text>');
});

it('renders html through the node compiler', function () {
    $compiler = new NodeProcessCompiler([
        'binary' => PHP_BINARY,
        'arguments' => ['-r', 'echo stream_get_contents(STDIN);'],
    ]);

    $html = MjmlComponentFactory::document([
        MjmlComponentFactory::text('Hi'),
    ])->renderHtml($compiler);

    expect($html)->toBe('<mjml><mj-text>Hi</mj-text></mjml>');
});

it('rejects renderHtml on nested components', function (MjmlComponentEnum $component) {
    $compiler = new class implements CompilesMjml
    {
        public function compile(string $mjmlMarkup): string
        {
            return $mjmlMarkup;
        }
    };

    expect(fn () => MjmlComponentFactory::make($component)->setContent('Hi')->renderHtml($compiler))
        ->toThrow(RuntimeException::class, 'must be called on the root MJML document');
})->with([
    MjmlComponentEnum::TEXT,
    MjmlComponentEnum::SECTION,
    MjmlComponentEnum::HEAD,
]);
