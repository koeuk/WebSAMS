<?php

namespace App\Enums;

enum DayOfWeek: string
{
    case Mon = 'mon';
    case Tue = 'tue';
    case Wed = 'wed';
    case Thu = 'thu';
    case Fri = 'fri';
    case Sat = 'sat';
    case Sun = 'sun';

    public function label(): string
    {
        return match($this) {
            self::Mon => 'Monday',
            self::Tue => 'Tuesday',
            self::Wed => 'Wednesday',
            self::Thu => 'Thursday',
            self::Fri => 'Friday',
            self::Sat => 'Saturday',
            self::Sun => 'Sunday',
        };
    }

    public static function options(): array
    {
        return array_map(fn ($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
    }
}
