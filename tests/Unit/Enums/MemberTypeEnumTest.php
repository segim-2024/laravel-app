<?php

namespace Tests\Unit\Enums;

use App\Enums\MemberTypeEnum;
use PHPUnit\Framework\TestCase;

class MemberTypeEnumTest extends TestCase
{
    /**
     * @dataProvider mb_type_매핑
     */
    public function test_mb_type을_회원_구분으로_변환한다(int|string|null $mbType, string $expected): void
    {
        $this->assertSame($expected, MemberTypeEnum::fromMbType($mbType)->value);
    }

    /**
     * @return array<string, array{int|string|null, string}>
     */
    public static function mb_type_매핑(): array
    {
        return [
            '0 미설정' => [0, 'unknown'],
            '1 어드민' => [1, 'admin'],
            '2 본부장' => [2, 'headquarters'],
            '3 학원' => [3, 'campus'],
            '4 학생' => [4, 'student'],
            '5 강사' => [5, 'teacher'],
            '문자열로 들어와도 처리' => ['4', 'student'],
            'null' => [null, 'unknown'],
            '정의되지 않은 값' => [99, 'unknown'],
        ];
    }

    /**
     * 레거시 코드로 확정된 두 값은 이름이 바뀌면 안 된다.
     */
    public function test_확정된_유형의_값이_고정되어_있다(): void
    {
        $this->assertSame('campus', MemberTypeEnum::Campus->value);
        $this->assertSame('student', MemberTypeEnum::Student->value);
    }

    /**
     * 라이브러리 API 의 level 값. 레거시 userLogin.php 의 변환과 동일해야 한다.
     *
     * @dataProvider 등급_매핑
     */
    public function test_회원_구분을_등급으로_변환한다(int $mbType, int $expected): void
    {
        $this->assertSame($expected, MemberTypeEnum::fromMbType($mbType)->libraryLevel());
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function 등급_매핑(): array
    {
        return [
            '학생 → 1' => [4, 1],
            '학원 → 2' => [3, 2],
            '어드민 → 3' => [1, 3],
            '본부장 → 0' => [2, 0],
            '강사 → 4' => [5, 4],
            '미설정 → 0' => [0, 0],
            '정의되지 않은 값 → 0' => [99, 0],
        ];
    }
}
