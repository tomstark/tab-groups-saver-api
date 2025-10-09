<?php

declare(strict_types=1);

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\Core\Traits\HandlesIncludesTrait;
use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Facade;

arch('All Module Resources should use ResourceResponseWithDataTrait')
    ->expect('App\Modules\*\Resources')
    ->toExtend(JsonResource::class)
    ->toUseTrait(ResourceResponseWithDataTrait::class);

arch('All Module Facades should use MockableFinalFacadeTrait')
    ->expect('App\Modules\**\*\Facades')
    ->toExtend(Facade::class)
    ->toUseTrait(MockableFinalFacadeTrait::class);

arch('All Module Controllers extend Core Controller')
    ->expect('App\Modules\*\HTTP\Controllers')
    ->toExtend(Controller::class)
    ->and(Controller::class)
    ->toUseTrait(HandlesIncludesTrait::class);
