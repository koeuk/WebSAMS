<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Graduated = 'graduated';
    case Suspended = 'suspended';
}
