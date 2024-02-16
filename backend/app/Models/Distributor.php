<?php

namespace App\Models;

use App\Casts\PgsqlArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Distributor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor query()
 *
 * @property int $id
 * @property string $region_id
 * @property int|null $city_id
 * @property string $name
 * @property string $status
 * @property string|null $email
 * @property string|null $web_site
 * @property string $phone
 * @property string $address
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Distributor whereWebSite($value)
 *
 * @mixin \Eloquent
 */
class Distributor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['region_id', 'city_id', 'name', 'status', 'email', 'web_site', 'phone', 'address'];

    public $timestamps = false;

    protected $casts = [
        'phone' => PgsqlArray::class,
        'email' => PgsqlArray::class,
        'web_site' => PgsqlArray::class,
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
