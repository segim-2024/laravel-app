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
            '1 관리자' => [1, 'admin'],
            '2 본부' => [2, 'headquarters'],
            '3 학원' => [3, 'campus'],
            '4 학생' => [4, 'student'],
            '5 기타' => [5, 'other'],
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
}
