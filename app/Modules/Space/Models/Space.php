<?php

declare(strict_types=1);

namespace App\Modules\Space\Models;

use App\Modules\Core\Scopes\OrderedByPositionAscScope;
use App\Modules\Space\Builders\SpaceQueryBuilder;
use App\Modules\User\Models\User;
use Carbon\CarbonImmutable;
use Database\Factories\SpaceFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $color - ToDo - likely cast to an enum
 * @property int $position
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property string $user_id
 * @property-read User $user
 *
 * @method static SpaceQueryBuilder<Space> query()
 *
 * @mixin SpaceQueryBuilder<Space>
 */
#[UseFactory(SpaceFactory::class)]
#[UseEloquentBuilder(SpaceQueryBuilder::class)]
#[ScopedBy([OrderedByPositionAscScope::class])]
final class Space extends Model implements Sortable
{
    /** @use HasFactory<SpaceFactory> */
    use HasFactory, HasUuids, SortableTrait;

    protected $fillable = ['name', 'slug'];

    /**
     * @return BelongsTo<User, covariant Space>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * @return HasMany<Window, covariant Space>
    //  */
    // public function windows(): HasMany
    // {
    //     return $this->hasMany(Window::class);
    // }

    /**
     * Groups the positioning by user (wouldn't want to re-position another User's Spaces also)
     *
     * @return SpaceQueryBuilder<Space>
     */
    public function buildSortQuery(): SpaceQueryBuilder
    {
        return self::query()->where('user_id', $this->user_id);
    }
}
