<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 *
 * @property int $id
 * @property string $lesson_uuid
 * @property string $series_uuid
 * @property string $volume_uuid
 * @property string $title
 * @property int $sort
 * @property bool $is_whale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property DoctorEssaySeries $series
 * @property DoctorEssayVolume $volume
 * @property DoctorEssayMaterial[]|Collection $materials
 */
class DoctorEssayLesson extends Model
{
    protected $casts = [
        'is_whale' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(DoctorEssaySeries::class, 'series_uuid', 'series_uuid');
    }

    public function volume(): BelongsTo
    {
        return $this->belongsTo(DoctorEssayVolume::class, 'volume_uuid', 'volume_uuid');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(DoctorEssayMaterial::class, 'lesson_uuid', 'lesson_uuid');
    }
}
