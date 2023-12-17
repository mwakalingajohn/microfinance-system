<?php

it('provides correct due dates', function () {
    $class = new class {
        use \App\Library\Traits\CanCalculateDueDates;
    };

    $initialDate = Carbon\Carbon::create(2021, 1, 1);
    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Daily,
        \App\Library\Enums\DueDateMethod::AnyDatetime
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 2));

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Daily,
        \App\Library\Enums\DueDateMethod::EndOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 2)->endOfDay());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Daily,
        \App\Library\Enums\DueDateMethod::StartOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 2)->startOfDay());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Weekly,
        \App\Library\Enums\DueDateMethod::AnyDatetime
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 8));

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Weekly,
        \App\Library\Enums\DueDateMethod::EndOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 8)->endOfWeek());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Weekly,
        \App\Library\Enums\DueDateMethod::StartOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 1, 8)->startOfWeek());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Monthly,
        \App\Library\Enums\DueDateMethod::AnyDatetime
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 2, 1));

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Monthly,
        \App\Library\Enums\DueDateMethod::EndOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 2, 1)->endOfMonth());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Monthly,
        \App\Library\Enums\DueDateMethod::StartOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2021, 2, 1)->startOfMonth());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Yearly,
        \App\Library\Enums\DueDateMethod::AnyDatetime
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2022, 1, 1));

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Yearly,
        \App\Library\Enums\DueDateMethod::EndOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2022, 1, 1)->endOfYear());

    $dueDate = $class->getDueDate(1,
        $initialDate,
        \App\Library\Enums\InterestPeriod::Yearly,
        \App\Library\Enums\DueDateMethod::StartOfPeriod
    );

    expect($dueDate)->toBeInstanceOf(Carbon\Carbon::class)
        ->toEqual(Carbon\Carbon::create(2022, 1, 1)->startOfYear());


});
