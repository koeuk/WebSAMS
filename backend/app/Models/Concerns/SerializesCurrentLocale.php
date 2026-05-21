<?php

namespace App\Models\Concerns;

trait SerializesCurrentLocale
{
    public function toArray(): array
    {
        $array = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $field) {
            $array[$field] = $this->getAttribute($field);
        }

        return $array;
    }
}
