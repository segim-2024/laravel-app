<?php

namespace App\Support;

/**
 * 그누보드 레거시 비밀번호 검증기.
 *
 * g5_member.mb_password 는 MySQL PASSWORD() 함수의 출력(`*` + 40자리 대문자 HEX, 총 41자)이다.
 * MySQL 8.0 에서 PASSWORD() 가 제거되었으므로 동일한 알고리즘을 PHP 로 재현한다.
 */
class GnuboardPasswordVerifier
{
    /**
     * MySQL PASSWORD() 출력 길이 (`*` 1자 + SHA1 HEX 40자)
     */
    private const HASH_LENGTH = 41;

    /**
     * 평문 비밀번호가 저장된 해시와 일치하는지 검사한다.
     *
     * 빈 비밀번호와 지원하지 않는 해시 형식은 항상 거부한다.
     * 운영에는 비밀번호가 비어 있는 회원이 존재하며(파머스 262건, 고래 68건),
     * 이들이 빈 문자열로 인증되어서는 안 된다.
     */
    public static function verify(string $plain, string $hash): bool
    {
        if ($plain === '' || ! self::isSupported($hash)) {
            return false;
        }

        return hash_equals(strtoupper($hash), self::hash($plain));
    }

    /**
     * MySQL 4.1+ PASSWORD() 와 동일한 해시를 생성한다.
     *
     * CONCAT('*', UPPER(HEX(SHA1(UNHEX(SHA1(str))))))
     */
    public static function hash(string $plain): string
    {
        return '*'.strtoupper(sha1(sha1($plain, true)));
    }

    /**
     * 검증 가능한 해시 형식인지 여부.
     */
    private static function isSupported(string $hash): bool
    {
        return strlen($hash) === self::HASH_LENGTH && str_starts_with($hash, '*');
    }
}
