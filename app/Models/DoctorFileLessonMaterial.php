<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class DoctorFileLessonMaterial
 *
 * @package App\Models
 * @property int $id
 * @property string $lesson_uuid
 * @property string $material_uuid
 * @property string $file_uuid
 * @property ?string $title
 * @property ?string $description
 * @property ?string $hex_color_code
 * @property string $created_at
 * @property string $updated_at
 * @property DoctorFileLesson $lesson
 * @property ?File $file
 */
class DoctorFileLessonMaterial extends Model
{
    protected $fillable = [
        'lesson_uuid',
        'material_uuid',
        'file_uuid',
        'title',
        'description',
    ];

    protected $hidden = [
        'file_uuid',
    ];


    public function lesson(): BelongsTo
    {
        return $this->belongsTo(DoctorFileLesson::class, 'lesson_uuid', 'lesson_uuid');
    }

    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'file_uuid');
    }
}
