<?php

declare(strict_types=1);

namespace App\Modules\Space\Actions;

use App\Modules\Core\Actions\Facades\UpdateModelPositionAction;
use App\Modules\Space\Actions\Facades\UpdateSpaceNameAction;
use App\Modules\Space\DTOs\UpdateSpaceDto;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

final readonly class UpdateSpaceAction
{
    /**
     * @throws ModelNotFoundException
     */
    public function run(User $user, UpdateSpaceDto $updateSpaceDto): Space
    {
        return DB::transaction(function () use ($user, $updateSpaceDto) {
            $space = Space::query()->bySlugForUserId($updateSpaceDto->slug, $user->id)->firstOrFail();

            if ($this->shouldUpdatePosition($updateSpaceDto->position)) {
                /** @var Space $space */
                $space = UpdateModelPositionAction::run($space, $updateSpaceDto->position);
            }

            if ($this->shouldUpdateName($updateSpaceDto->name)) {
                $space = UpdateSpaceNameAction::run($space, $updateSpaceDto->name);
                /** @noRector ReturnEarlyIfVariableRector */
            }

            // ToDo
            // if ($this->shouldSetColor($updateSpaceDto->color)) {
            //     $space = UpdateSpaceColorAction::run($space, $updateSpaceDto->color);
            // }

            return $space;
        });

    }

    private function shouldUpdatePosition(?int $position): bool
    {
        return is_int($position);
    }

    private function shouldUpdateName(?string $name): bool
    {
        return is_string($name);
    }
}
