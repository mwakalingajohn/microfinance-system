<?php

namespace Tests\Library\Traits;

use App\Library\Traits\HasInternalResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

uses(\Tests\TestCase::class, RefreshDatabase::class);

it('can set response', function () {
    $trait = new class {
        use HasInternalResponse;
    };

    $response = $trait->setResponse(
        success: true,
        message: 'test',
        data: ['test' => 'test']
    );

    expect($response->succeeded())->toBeTrue()
        ->and($response->message)->toBe('test')
        ->and($response->data)->toBe(['test' => 'test']);
});
