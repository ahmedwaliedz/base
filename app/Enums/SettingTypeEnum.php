<?php

namespace App\Enums;

enum SettingTypeEnum: string
{
    case IMAGE      = 'image';
    case STRING     = 'string';
    case INTEGER    = 'integer';
    case BOOLEAN    = 'boolean';
    case ARRAY      = 'array';
    case JSON       = 'json';
    case FILE       = 'file';
}
