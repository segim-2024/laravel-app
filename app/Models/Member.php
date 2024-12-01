<?php

namespace App\Models;

use App\Enums\LibraryProductSubscribeStateEnum;
use App\Models\Interfaces\MemberInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $mb_no PK
 * @property string $mb_id 계정
 * @property string $mb_password 비밀번호
 * @property string $mb_name 이름
 * @property string $mb_nick 닉네임
 * @property string $mb_nick_date ??
 * @property string $mb_email 이메일
 * @property string $mb_hp 핸드폰
 * @property string $mb_homepage 홈페이지
 * @property string $mb_level 레벨
 * @property ?string $toss_customer_key 토스 Customer 키
 * @property ?string $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MemberCash $cash via cash() relationship getter magic method
 * @property MemberCard[]|Collection[] $cards via cards() relationship getter magic method
 * @property MemberSubscribeProduct[]|Collection $productSubscribes via subscribes() relationship getter magic method
 * @property LibraryProductSubscribe $librarySubscribe via librarySubscribes() relationship getter magic method
 */
class Member extends Authenticatable implements MemberInterface
{
    use HasApiTokens;

    protected $table = "g5_member";

    protected $primaryKey = 'mb_no';

    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string, string>
     */
    protected $hidden = [
        'mb_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'mb_id' => 'string',
        'mb_name' => 'string',
    ];

    public function cash():HasOne
    {
        return $this->hasOne(MemberCash::class, 'member_id', 'mb_id');
    }

    public function cards():HasMany
    {
        return $this->hasMany(MemberCard::class, 'member_id');
    }

    public function productSubscribes():HasMany
    {
        return $this->hasMany(MemberSubscribeProduct::class, 'member_id', 'mb_id');
    }

    public function librarySubscribe():HasOne
    {
        return $this->hasOne(LibraryProductSubscribe::class, 'member_id', 'mb_id')
            ->where('state', "!=", LibraryProductSubscribeStateEnum::Unsubscribe);
    }
}
