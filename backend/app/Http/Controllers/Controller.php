<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected const PER_PAGE_OPTIONS = [10, 15, 20, 50, 100];
    protected const DEFAULT_PER_PAGE = 20;

    protected function limit(): int
    {
        $requested = (int) request('per_page', self::DEFAULT_PER_PAGE);
        return in_array($requested, self::PER_PAGE_OPTIONS, true) ? $requested : self::DEFAULT_PER_PAGE;
    }
}
