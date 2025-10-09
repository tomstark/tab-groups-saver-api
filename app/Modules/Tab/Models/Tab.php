<?php

declare(strict_types=1);

namespace App\Modules\Tab\Models;

use App\Modules\Core\Scopes\OrderedByPositionAscScope;
use App\Modules\Tab\Builders\TabQueryBuilder;
use App\Modules\TabGroup\Models\TabGroup;
use Carbon\CarbonImmutable;
use Database\Factories\TabFactory;
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
 * @property string $title
 * @property string $url
 * @property string $icon
 * @property int $position
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property string $tab_group_id
 * @property-read TabGroup $tabGroup
 *
 * @method static TabQueryBuilder<Tab> query()
 *
 * @mixin TabQueryBuilder<Tab>
 */
#[UseFactory(TabFactory::class)]
#[UseEloquentBuilder(TabQueryBuilder::class)]
#[ScopedBy([OrderedByPositionAscScope::class])]
final class Tab extends Model implements Sortable
{
    /** @use HasFactory<TabFactory> */
    use HasFactory, HasUuids, SortableTrait;

    protected $fillable = [
        'title',
        'url',
        'icon',
    ];

    /**
     * @return BelongsTo<TabGroup, covariant Tab>
     */
    public function tabGroup(): BelongsTo
    {
        return $this->belongsTo(TabGroup::class);
    }

    /**
     * Groups the positioning by tab groups (wouldn't want to re-position another TabGroup's Tab also)
     *
     * @return TabQueryBuilder<Tab>
     */
    public function buildSortQuery(): TabQueryBuilder
    {
        return self::query()->where('tab_group_id', $this->tab_group_id);
    }
}
