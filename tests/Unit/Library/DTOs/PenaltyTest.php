<?php

namespace Tests\Library\DTOs;

use App\Library\DTOs\Penalty;
use PHPUnit\Framework\TestCase;

it('can be instantiated', function () {
    $penalty = new Penalty();

    $this->assertInstanceOf(Penalty::class, $penalty);
});
