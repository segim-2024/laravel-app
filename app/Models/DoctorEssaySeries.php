<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @package App\Models
 * @property int $id
 * @property string $series_uuid
 * @property string $title
 * @property int $sort
 * @property bool $is_whale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property DoctorEssayVolume[]|Collection $volumes
 * @property DoctorEssayLesson[]|Collection $lessons
 */
class DoctorEssaySeries extends Model
{
    protected $casts = [
        'is_whale' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string, string>
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function volumes(): HasMany
    {
        return $this->hasMany(DoctorEssayVolume::class, 'series_uuid', 'series_uuid');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(DoctorEssayLesson::class, 'series_uuid', 'series_uuid');
    }
}
