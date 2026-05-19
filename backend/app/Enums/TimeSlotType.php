<?php

namespace App\Enums;

enum TimeSlotType: string
{
    case Morning   = 'morning';
    case Afternoon = 'afternoon';
    case Evening   = 'evening';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function options(): array
    {
        return array_map(fn ($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
    }
}
