<?php

namespace App\Models;

use App\Models\Interfaces\CashInterface;
use App\Models\Interfaces\MemberInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;


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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property WhaleMemberCash $cash via cash() relationship getter magic method
 */
class WhaleMember extends Authenticatable implements MemberInterface
{
    use HasApiTokens;

    protected $connection = "mysql_whale";
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

    /**
     * 사용자 토큰을 생성합니다.
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null): NewAccessToken
    {
        $plainTextToken = $this->generateTokenString();
        $token = $this->tokens()
            ->create([
                'name' => $name,
                'token' => hash('sha256', $plainTextToken),
                'abilities' => $abilities,
                'expires_at' => $expiresAt,
            ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }

    /**
     * 사용자의 모든 토큰을 조회합니다.
     */
    public function tokens(): MorphMany
    {
        return $this->setConnection('mysql_sg')
            ->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable');
    }

    public function cash(): HasOne
    {
        return $this->hasOne(WhaleMemberCash::class, 'member_id', 'mb_id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(WhaleMemberCard::class, 'member_id', 'mb_id');
    }

    public function productSubscribes(): HasMany
    {
        return $this->hasMany(WhaleMemberSubscribeProduct::class, 'member_id', 'mb_id');
    }

    /**
     * @inheritDoc
     */
    public function getMemberId(): string
    {
        return $this->mb_id;
    }

    /**
     * @inheritDoc
     */
    public function getCash(): ?CashInterface
    {
        return $this->cash;
    }

    public function isWhale(): bool
    {
        return true;
    }

    /**
     * 고래영어는 마일리지 미지원
     */
    public function hasMileageAccess(): bool
    {
        return false;
    }
}
