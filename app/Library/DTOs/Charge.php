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
}
