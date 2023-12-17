<?php

// create a pest test
use App\Library\DTOs\Charge;


it('can instantiate Charge class and toArray works', function () {
    $charge = new Charge(
        id: "1",
        label: "Test Label",
        on: "Test",
        type: "Type Test",
        of: "Of Test",
        value: 10,
        amount: 20,
        chargedAmount: 30
    );

    expect($charge)->toBeInstanceOf(Charge::class)
        ->and($charge->toArray())->toBeArray()
        ->and($charge->toArray())->toHaveKeys(["id", "label", "on", "type", "of", "value", "amount", "chargedAmount"]);
});

it('Check constructor with no parameters', function () {
    $charge = new Charge();

    expect($charge)->toBeInstanceOf(Charge::class)
        ->and($charge->id)->toBeNull()
        ->and($charge->label)->toEqual('')
        ->and($charge->on)->toEqual('')
        ->and($charge->type)->toEqual('')
        ->and($charge->of)->toEqual('')
        ->and($charge->value)->toEqual(0)
        ->and($charge->amount)->toEqual(0)
        ->and($charge->chargedAmount)->toEqual(0);
});

it('Check constructor with parameters', function () {
    $charge = new Charge('123', 'test label', 'test on', 'charge type', 'charge of', 100, 200, 300);

    expect($charge->id)->toEqual('123')
        ->and($charge->label)->toEqual('test label')
        ->and($charge->on)->toEqual('test on')
        ->and($charge->type)->toEqual('charge type')
        ->and($charge->of)->toEqual('charge of')
        ->and($charge->value)->toEqual(100)
        ->and($charge->amount)->toEqual(200)
        ->and($charge->chargedAmount)->toEqual(300);
});
