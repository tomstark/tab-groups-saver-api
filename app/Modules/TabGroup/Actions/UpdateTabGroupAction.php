<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\Actions;

use App\Modules\Core\Actions\Facades\UpdateModelPositionAction;
use App\Modules\TabGroup\Actions\Facades\UpdateTabGroupNameAction;
use App\Modules\TabGroup\DTOs\UpdateTabGroupDto;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

final readonly class UpdateTabGroupAction
{
    /**
     * @throws ModelNotFoundException
     */
    public function run(User $user, UpdateTabGroupDto $updateTabGroupDto): TabGroup
    {
        return DB::transaction(function () use ($user, $updateTabGroupDto) {
            $tabGroup = TabGroup::query()->byIdForUserId($updateTabGroupDto->id, $user->id)->firstOrFail();

            if ($this->shouldUpdatePosition($updateTabGroupDto->position)) {
                /** @var TabGroup $tabGroup */
                $tabGroup = UpdateModelPositionAction::run($tabGroup, $updateTabGroupDto->position);
            }

            if ($this->shouldUpdateName($updateTabGroupDto->name)) {
                $tabGroup = UpdateTabGroupNameAction::run($tabGroup, $updateTabGroupDto->name);
                /** @noRector ReturnEarlyIfVariableRector */
            }

            // ToDo
            // if ($this->shouldSetColor($updateTabGroupDto->color)) {
            //     $tabGroup = UpdateTabGroupColorAction::run($tabGroup, $updateTabGroupDto->color);
            // }

            return $tabGroup;
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
