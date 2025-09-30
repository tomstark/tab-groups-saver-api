<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Eloquent;

use App\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of User
 *
 * @extends Builder<TModelClass>
 */
final class UserQueryBuilder extends Builder {}
