<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Enums;

enum MjmlComponentEnum
{
    // Core Document Structural Elements
    case MJML;
    case HEAD;
    case BODY;
    case SECTION;
    case COLUMN;
    case GROUP;

    // Body Content Elements
    case TEXT;
    case IMAGE;
    case BUTTON;
    case DIVIDER;
    case SPACER;
    case HERO;
    case RAW;

    // Head Configuration Elements
    case ATTRIBUTES;
    case STYLE;
    case FONT;
    case TITLE;
    case PREVIEW;
}
