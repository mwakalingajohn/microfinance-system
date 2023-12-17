<?php

namespace Library\DTOs;

use App\Library\DTOs\InternalResponse;
use PHPUnit\Framework\TestCase;


it('can be created', function () {
    $response = new InternalResponse();

    expect($response)->toBeInstanceOf(InternalResponse::class);
});

it('can be created with data', function () {
    $response = new InternalResponse(['foo' => 'bar']);

    expect($response)->toBeInstanceOf(InternalResponse::class)
        ->and($response->data)->toBeArray()
        ->and($response->data)->toHaveKey('foo')
        ->and($response->data['foo'])->toEqual('bar');
});

it('can be created with success', function () {
    $response = new InternalResponse([], true);

    expect($response)->toBeInstanceOf(InternalResponse::class)
        ->and($response->success)->toBeTrue();
});

it('can be created with message', function () {
    $response = new InternalResponse([], false, 'foo');

    expect($response)->toBeInstanceOf(InternalResponse::class)
        ->and($response->message)->toEqual('foo');
});

it('can be created with all parameters', function () {
    $response = new InternalResponse(['foo' => 'bar'], true, 'baz');

    expect($response)->toBeInstanceOf(InternalResponse::class)
        ->and($response->data)->toBeArray()
        ->and($response->data)->toHaveKey('foo')
        ->and($response->data['foo'])->toEqual('bar')
        ->and($response->success)->toBeTrue()
        ->and($response->message)->toEqual('baz');
});

it('can check if it succeeded', function () {
    $response = new InternalResponse([], true);

    expect($response->succeeded())->toBeTrue();
});

it('can check if it failed', function () {
    $response = new InternalResponse([], false);

    expect($response->failed())->toBeTrue();
});

