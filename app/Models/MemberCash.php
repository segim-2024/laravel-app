<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $member_id 학원 No
 * @property int $amount 캐쉬 잔액
 * @property Member $member via member() relationship getter magic method
 */
class MemberCash extends Model
{
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'member_id';

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'member_id' => 'string',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'mb_id');
    }
}
