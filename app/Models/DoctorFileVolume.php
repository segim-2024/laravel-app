<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class DoctorFileVolume
 *
 * @package App\Models
 * @property int $id
 * @property string $series_uuid
 * @property string $volume_uuid
 * @property string $title
 * @property string|null $poster_image_uuid
 * @property string|null $description
 * @property int $sort
 * @property bool $is_published
 * @property bool $is_whale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property DoctorFileSeries $series
 * @property DoctorFileLesson[]|Collection $lessons
 * @property ?File $poster
 */
class DoctorFileVolume extends Model
{
    protected $casts = [
        'is_whale' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected $hidden = [
        'id'.
        'created_at',
        'updated_at',
        'poster_image_uuid',
    ];

    public function series(): HasMany
    {
        return $this->hasMany(DoctorFileSeries::class, 'series_uuid', 'series_uuid');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(DoctorFileLesson::class, 'volume_uuid', 'volume_uuid');
    }

    public function poster(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'poster_image_uuid');
    }
}
