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

    public function succeeded()
    {
        return $this->success;
    }
    

    public function failed()
    {
        return !$this->success;
    }
}
