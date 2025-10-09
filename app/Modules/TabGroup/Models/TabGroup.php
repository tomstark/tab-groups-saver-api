<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\Models;

use App\Modules\Core\Scopes\OrderedByPositionAscScope;
use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Builders\TabGroupQueryBuilder;
use App\Modules\Window\Models\Window;
use Carbon\CarbonImmutable;
use Database\Factories\TabGroupFactory;
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
 * @property string $window_id
 * @property-read Window $window
 * @property-read Collection<int, Tab> $tabs
 *
 * @method static TabGroupQueryBuilder<TabGroup> query()
 *
 * @mixin TabGroupQueryBuilder<TabGroup>
 */
#[UseFactory(TabGroupFactory::class)]
#[UseEloquentBuilder(TabGroupQueryBuilder::class)]
#[ScopedBy([OrderedByPositionAscScope::class])]
final class TabGroup extends Model implements Sortable
{
    /** @use HasFactory<TabGroupFactory> */
    use HasFactory, HasUuids, SortableTrait;

    protected $fillable = ['name'];

    /**
     * @return BelongsTo<Window, covariant TabGroup>
     */
    public function window(): BelongsTo
    {
        return $this->belongsTo(Window::class);
    }

    /**
     * @return HasMany<Tab, covariant TabGroup>
     */
    public function tabs(): HasMany
    {
        return $this->hasMany(Tab::class);
    }

    /**
     * Groups the positioning by window (wouldn't want to re-position another Window's TabGroup also)
     *
     * @return TabGroupQueryBuilder<TabGroup>
     */
    public function buildSortQuery(): TabGroupQueryBuilder
    {
        return self::query()->where('window_id', $this->window_id);
    }
}
