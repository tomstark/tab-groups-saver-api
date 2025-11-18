<?php

declare(strict_types=1);

namespace App\Modules\Space\Actions;

use App\Modules\Core\Exceptions\ModelNameValidationException;
use App\Modules\Space\Models\Space;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final readonly class UpdateSpaceNameAction
{
    /**
     * @throws ModelNotFoundException
     * @throws ModelNameValidationException
     */
    public function run(Space $space, string $name): Space
    {
        // ToDo - make validator file (with set messages) for this model's rules, just fleshing things out for now
        try {
            $domainValidationRules = ['required', 'string', 'min:1', 'max:30'];

            /** @var array{name: string} $validData */
            $validData = Validator::make(['name' => $name], ['name' => $domainValidationRules])->validate();
        } catch (ValidationException $exception) {
            throw new ModelNameValidationException($exception->getMessage());
        }

        $space->name = $validData['name'];
        $space->save();

        return $space;
    }
}
