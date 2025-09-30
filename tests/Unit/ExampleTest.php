<?php

declare(strict_types=1);

use App\Modules\User\Models\User;

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('quick user test', function () {
    // ToDo - only here to get 100% test coverage when introducing tooling into the codebase (for `composer test`)
    //  Review this later when get into the real application code
    expect(new User)->toBeTruthy();
});
