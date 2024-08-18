<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $uuid
 * @property string $extension
 * @property string $server_name
 * @property string $original_name
 * @property string $full_path
 * @property string $path
 * @property int $size
 * @property string $created_at
 * @property string $updated_at
 */
class File extends Model
{
    protected $fillable = [
        'uuid',
        'extension',
        'server_name',
        'original_name',
        'full_path',
        'path',
        'size',
    ];

    protected $hidden = [
        'id',
        'uuid',
        'server_name',
        'extension',
        'path',
        'created_at',
        'updated_at',
    ];
}
