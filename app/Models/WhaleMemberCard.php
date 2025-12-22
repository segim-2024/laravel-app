<?php

namespace App\Models;

use App\Models\Interfaces\CardInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $member_id 회원 ID
 * @property string $name 카드명
 * @property string $number 마스킹된 카드 번호
 * @property string $key 빌링키
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property WhaleMember $member via member() relationship getter magic method
 */
class WhaleMemberCard extends Model implements CardInterface
{
    protected $connection = 'mysql_whale';
    protected $table = 'member_cards';

    protected $hidden = [
        'member_id',
        'key',
    ];

    protected $casts = [
        'id' => 'string',
        'member_id' => 'string',
        'key' => 'string',
        'number' => 'string',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(WhaleMember::class, 'member_id', 'mb_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMemberId(): string
    {
        return $this->member_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}