<?php

namespace Tests\Unit\Support;

use App\Support\GnuboardPasswordVerifier;
use PHPUnit\Framework\TestCase;

/**
 * 그누보드 레거시 비밀번호 검증기 테스트.
 *
 * 운영 g5_member 의 mb_password 는 전량 길이 41 (MySQL PASSWORD() 출력)이다.
 */
class GnuboardPasswordVerifierTest extends TestCase
{
    /**
     * MySQL PASSWORD() 와 동일한 해시를 만든다.
     */
    private function mysqlPassword(string $plain): string
    {
        return '*'.strtoupper(sha1(sha1($plain, true)));
    }

    public function test_올바른_비밀번호를_검증한다(): void
    {
        $this->assertTrue(
            GnuboardPasswordVerifier::verify('password', '*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19')
        );
    }

    public function test_mysql_password_함수의_알려진_출력과_일치한다(): void
    {
        // MySQL 4.1+ : CONCAT('*', UPPER(HEX(SHA1(UNHEX(SHA1(str))))))
        $this->assertTrue(GnuboardPasswordVerifier::verify('abc', '*0D3CED9BEC10A777AEC23CCC353A8C08A633045E'));
        $this->assertTrue(GnuboardPasswordVerifier::verify('1234', '*A4B6157319038724E3560894F7F932C8886EBFCF'));
    }

    public function test_틀린_비밀번호는_거부한다(): void
    {
        $this->assertFalse(
            GnuboardPasswordVerifier::verify('wrong', $this->mysqlPassword('password'))
        );
    }

    public function test_멀티바이트_비밀번호를_처리한다(): void
    {
        $this->assertTrue(
            GnuboardPasswordVerifier::verify('테스트비번!', $this->mysqlPassword('테스트비번!'))
        );
    }

    public function test_해시가_소문자여도_검증한다(): void
    {
        $this->assertTrue(
            GnuboardPasswordVerifier::verify('password', strtolower($this->mysqlPassword('password')))
        );
    }

    /**
     * 운영에 비밀번호 미설정 회원이 존재한다 (파머스 262건, 고래 68건).
     * 빈 해시로는 어떤 비밀번호로도 인증되어서는 안 된다.
     */
    public function test_빈_해시는_항상_거부한다(): void
    {
        $this->assertFalse(GnuboardPasswordVerifier::verify('password', ''));
        $this->assertFalse(GnuboardPasswordVerifier::verify('', ''));
    }

    public function test_빈_비밀번호는_항상_거부한다(): void
    {
        $this->assertFalse(
            GnuboardPasswordVerifier::verify('', $this->mysqlPassword(''))
        );
    }

    /**
     * 길이 41이 아닌 해시(운영에 길이 3짜리 1건 존재)는 형식 미지원으로 거부한다.
     */
    public function test_지원하지_않는_해시_형식은_거부한다(): void
    {
        $this->assertFalse(GnuboardPasswordVerifier::verify('password', 'abc'));
        $this->assertFalse(GnuboardPasswordVerifier::verify('password', str_repeat('a', 40)));
        $this->assertFalse(GnuboardPasswordVerifier::verify('password', password_hash('password', PASSWORD_BCRYPT)));
    }

    public function test_별표로_시작하지_않는_41자_해시는_거부한다(): void
    {
        $this->assertFalse(
            GnuboardPasswordVerifier::verify('password', 'X'.substr($this->mysqlPassword('password'), 1))
        );
    }
}
