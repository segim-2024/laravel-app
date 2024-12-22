<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @package App\Models
 * @property int $id
 * @property string $lesson_uuid
 * @property string $material_uuid
 * @property string $file_uuid
 * @property ?string $title
 * @property ?string $hex_color_code
 * @property ?string $bg_hex_color_code
 * @property string $created_at
 * @property string $updated_at
 * @property DoctorEssayLesson $lesson
 * @property File $file
 */
class DoctorEssayMaterial extends Model
{
    protected $fillable = [
        'lesson_uuid',
        'material_uuid',
        'file_uuid',
        'title',
        'hex_color_code',
        'bg_hex_color_code'
    ];

    protected $hidden = [
        'file_uuid',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(DoctorEssayLesson::class, 'lesson_uuid', 'lesson_uuid');
    }

    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'file_uuid');
    }
}
