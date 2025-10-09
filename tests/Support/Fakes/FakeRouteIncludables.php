<?php

declare(strict_types=1);

namespace Tests\Support\Fakes;

use App\Modules\Core\Contracts\Includable;

final class FakeRouteIncludables implements Includable
{
    public function map(): array
    {
        return [
            'hello-world' => 'helloWorld',
            'lorem.ipsum' => 'dolor.sitAmet',
        ];
    }
}
