<?php

declare(strict_types=1);

namespace App\Modules\Space\Actions;

use App\Modules\Space\Actions\Facades\EnsureUniqueUserSpaceNameAction;
use App\Modules\Space\DTOs\CreateSpaceDto;
use App\Modules\Space\Events\SpaceCreated;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class CreateSpaceAction
{
    public function run(User $user, CreateSpaceDto $spaceDto): Space
    {
        return DB::transaction(static function () use ($user, $spaceDto) {
            EnsureUniqueUserSpaceNameAction::run($user, $spaceDto->name);

            /** @var Space $space */
            $space = $user->spaces()->create([
                'name' => $spaceDto->name,
                // ToDo - create separate action for slug creation & cover more requirements (unique per user etc)
                'slug' => Str::slug($spaceDto->name),
            ]);

            DB::afterCommit(static fn () => event(new SpaceCreated($space)));

            return $space;
        });
    }
}
