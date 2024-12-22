<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id
 * @property string $title
 * @property string $content
 * @property boolean $is_whale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class DoctorEssayNotice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'title',
        'content',
        'is_whale',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
