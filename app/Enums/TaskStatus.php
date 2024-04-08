<?php

namespace App\Enums;

enum TaskStatus: string
{
    case IMPORTANT = 'important';
    case SIMPLE = 'simple';
    case UNIMPORTANT = 'unimportant';
    case NONURGENT = 'nonurgent';


    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}
