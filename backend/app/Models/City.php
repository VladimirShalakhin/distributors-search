<?php

namespace App\Models;

use Database\Factories\CityFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $region_id
 *
 * @method static Builder|City newModelQuery()
 * @method static Builder|City newQuery()
 * @method static Builder|City query()
 * @method static Builder|City whereId($value)
 * @method static Builder|City whereName($value)
 * @method static Builder|City whereRegionId($value)
 *
 * @property-read Collection<int, Distributor> $distributors
 * @property-read int|null $distributors_count
 * @property-read Region $region
 *
 * @method static CityFactory factory($count = null, $state = [])
 *
 * @mixin Eloquent
 */
class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'region_id'];

    public $timestamps = false;

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }
}
