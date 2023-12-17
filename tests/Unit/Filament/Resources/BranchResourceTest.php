<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\BranchResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

uses(\Tests\TestCase::class);

it('can render page', function () {
    $this->get(BranchResource::getUrl('index'))->assertSuccessful();
});
