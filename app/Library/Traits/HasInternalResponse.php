<?php

namespace App\Library\Traits;

use App\Library\DTOs\InternalResponse;
use Illuminate\Support\Fluent;

trait HasInternalResponse
{
    protected array $data = [];

    protected bool $success = false;

    protected string $message = '';

    public function response(): InternalResponse
    {
        return new InternalResponse(
            data: $this->data,
            success: $this->success,
            message: $this->message
        );
    }

    public function setResponse(
        bool $success,
        string $message = "",
        array $data = [],
    ): InternalResponse {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        return $this->response();
    }
}
