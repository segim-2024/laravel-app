<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class DoctorFileLesson
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
 * @property DoctorFileSeries $series
 * @property DoctorFileVolume $volume
 * @property DoctorFileLessonMaterial[]|Collection $materials
 * @property ?File $zip
 */
class DoctorFileLesson extends Model
{
    protected $casts = [
        'is_whale' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'file_uuid',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(DoctorFileSeries::class, 'series_uuid', 'series_uuid');
    }

    public function volume(): BelongsTo
    {
        return $this->belongsTo(DoctorFileVolume::class, 'volume_uuid', 'volume_uuid');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(DoctorFileLessonMaterial::class, 'lesson_uuid', 'lesson_uuid');
    }

    public function zip(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'lesson_uuid');
    }
}
