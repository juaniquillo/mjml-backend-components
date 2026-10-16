<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Enums;

enum MjmlComponentEnum: string
{
    // Core Document Structural Elements
    case MJML = 'mjml';
    case HEAD = 'head';
    case BODY = 'body';
    case SECTION = 'section';
    case COLUMN = 'column';
    case GROUP = 'group';

    // Body Content Elements
    case TEXT = 'text';
    case IMAGE = 'image';
    case BUTTON = 'button';
    case DIVIDER = 'divider';
    case SPACER = 'spacer';
    case HERO = 'hero';
    case RAW = 'raw';

    // Head Configuration Elements
    case ATTRIBUTES = 'attributes';
    case ALL = 'all';
    case SELECTOR = 'selector';
    case MJML_CLASS = 'class';
    case STYLE = 'style';
    case FONT = 'font';
    case TITLE = 'title';
    case PREVIEW = 'preview';
}
