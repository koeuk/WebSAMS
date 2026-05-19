<?php

namespace App\Enums;

enum YearLevel: string
{
    case Year1 = '1';
    case Year2 = '2';
    case Year3 = '3';
    case Year4 = '4';

    public function label(): string
    {
        return 'Year ' . $this->value;
    }

    public static function options(): array
    {
        return array_map(fn ($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
    }
}
