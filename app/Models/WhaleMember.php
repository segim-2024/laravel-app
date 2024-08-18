<?php

namespace App\Models;

use App\Models\Interfaces\MemberInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;

class WhaleMember extends Model implements MemberInterface
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
}
