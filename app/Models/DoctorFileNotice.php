<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class DoctorFileNotice
 * @package App\Models
 *
 * @property int $id
 * @property string $member_id
 * @property string $title
 * @property string $content
 * @property boolean $is_whale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class DoctorFileNotice extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];
}
