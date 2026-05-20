<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function limit(): int
    {
        return 20;
    }
}
