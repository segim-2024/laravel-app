<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id 학원 No
 * @property string $name 카드명
 * @property string $number 마스킹된 카드 번호
 * @property string $key 빌링키
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Member $member via member() relationship getter magic method
 */
class MemberCard extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string, string>
     */
    protected $hidden = [
        'member_id',
        'key',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'id' => 'string',
        'member_id' => 'string',
        'key' => 'string',
        'number' => 'string',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }
}
