<?php

declare(strict_types=1);

arch('No debugging calls are used')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();
