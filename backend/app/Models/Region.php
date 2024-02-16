<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Region
 *
 * @property int|null $id
 * @property string $region_id
 * @property string $region_name
 * @property string $county
 * @property int|null $center_id
 * @property int $internal_id
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereInternalId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Distributor> $distributors
 * @property-read int|null $distributors_count
 * @method static \Database\Factories\RegionFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Region extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['internal_id', 'region_name', 'county'];

    public $timestamps = false;

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function distributors(): HasManyThrough
    {
        return $this->hasManyThrough(Distributor::class, City::class);
    }
}
