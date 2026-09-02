<?php

namespace App\Enums;

/**
 * g5_member.mb_type 회원 구분.
 *
 * Campus(3)/Student(4)만 레거시 코드로 확정된 값이고, 나머지는 건수와 데이터 구조로
 * 추론한 것이다. 응답에 값을 실어야 하므로 이름은 부여하되 의미를 단정하지 않는다.
 *
 * mb_level(그누보드 권한 레벨)로는 회원 유형을 판별할 수 없다. 파머스 기준
 * mb_level=1 에 학원 766명과 학생 183명이 섞여 있다.
 */
enum MemberTypeEnum: string
{
    /** mb_type=0 또는 미설정 (파머스 1건) */
    case Unknown = 'unknown';

    /** mb_type=1 (파머스 6건, 고래 1건) */
    case Admin = 'admin';

    /** mb_type=2 (파머스 42건, 고래 34건) */
    case Headquarters = 'headquarters';

    /** mb_type=3 학원. 소속 캠퍼스(mb_4)는 파머스 1,359건·고래 366건 전원 비어 있다 */
    case Campus = 'campus';

    /** mb_type=4 학생. mb_4 가 100% 채워져 있다 */
    case Student = 'student';

    /** mb_type=5 (파머스 전용 2,682건). 학생과 구조가 같고 소속 학원 매칭률 98.3% */
    case Other = 'other';

    public function isCampus(): bool
    {
        return $this === self::Campus;
    }

    /**
     * mb_type 컬럼값을 유형으로 변환한다. 정의되지 않은 값은 Unknown 으로 떨어진다.
     */
    public static function fromMbType(int|string|null $mbType): self
    {
        return match ((int) $mbType) {
            1 => self::Admin,
            2 => self::Headquarters,
            3 => self::Campus,
            4 => self::Student,
            5 => self::Other,
            default => self::Unknown,
        };
    }
}
