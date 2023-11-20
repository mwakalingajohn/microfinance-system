<?php

namespace App\Library\DTOs;

class Charge
{
    public function __construct(
        public string $label = "",
        public string $on = "",
        public string $type = "",
        public string $of = "",
        public mixed $value = 0,
        public mixed $amount = 0
    ) {
    }

    public function toArray()
    {
        return [
            "label" => $this->label,
            "on" => $this->on,
            "type" => $this->type,
            "of" => $this->of,
            "value" => $this->value,
            "amount" => $this->amount
        ];
    }
}
