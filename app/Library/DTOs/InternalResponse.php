<?php

namespace App\Library\DTOs;

class InternalResponse
{
    public function __construct(
        public array $data = [],
        public bool $success = false,
        public string $message = '',
    ) {
    }
}
