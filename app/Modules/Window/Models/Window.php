<?php

declare(strict_types=1);

namespace App\Modules\Window\Models;

use App\Modules\Core\Scopes\OrderedByPositionAscScope;
use App\Modules\Space\Models\Space;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\Window\Builders\WindowQueryBuilder;
use Carbon\CarbonImmutable;
use Database\Factories\WindowFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property string $id
 * @property string $name
 * @property int $position
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property string $space_id
 * @property-read Space $space
 * @property-read Collection<int, TabGroup> $tabGroups
 *
 * @method static WindowQueryBuilder<Window> query()
 *
 * @mixin WindowQueryBuilder<Window>
 */
#[UseFactory(WindowFactory::class)]
#[UseEloquentBuilder(WindowQueryBuilder::class)]
#[ScopedBy([OrderedByPositionAscScope::class])]
final class Window extends Model implements Sortable
{
    /** @use HasFactory<WindowFactory> */
    use HasFactory, HasUuids, SortableTrait;

    protected $fillable = ['name'];

    /**
     * @return BelongsTo<Space, covariant Window>
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * @return HasMany<TabGroup, covariant Window>
     */
    public function tabGroups(): HasMany
    {
        return $this->hasMany(TabGroup::class);
    }

    /**
     * Groups the positioning by space (wouldn't want to re-position another Space's Window also)
     *
     * @return WindowQueryBuilder<Window>
     */
    public function buildSortQuery(): WindowQueryBuilder
    {
        return self::query()->where('space_id', $this->space_id);
    }
}
