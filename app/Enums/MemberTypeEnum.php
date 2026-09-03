<?php

namespace App\Enums;

/**
 * g5_member.mb_type 회원 구분.
 *
 * 값의 의미는 파머스 관리자 화면의 라벨로 확정되었다
 * (/yc5/adm/member_form.php 의 라디오 라벨, member_list_new_t.php 의 "강사 관리").
 *
 * mb_level(그누보드 게시판 권한 레벨)로는 회원 유형을 판별할 수 없다.
 * 파머스 기준 mb_level=1 에 학원 766명과 학생 183명이 섞여 있다.
 */
enum MemberTypeEnum: string
{
    /** mb_type=0 또는 미설정 (파머스 1건) */
    case Unknown = 'unknown';

    /** mb_type=1 어드민 (파머스 6건, 고래 1건) */
    case Admin = 'admin';

    /** mb_type=2 본부장 (파머스 42건, 고래 34건) */
    case Headquarters = 'headquarters';

    /** mb_type=3 학원. 소속 캠퍼스(mb_4)는 파머스 1,359건·고래 366건 전원 비어 있다 */
    case Campus = 'campus';

    /** mb_type=4 학생. mb_4 에 소속 학원의 mb_id 를 담는다 */
    case Student = 'student';

    /** mb_type=5 강사. 파머스 전용(2,682건)이며 학생과 같이 mb_4 에 소속 학원을 담는다 */
    case Teacher = 'teacher';

    public function isCampus(): bool
    {
        return $this === self::Campus;
    }

    /**
     * 라이브러리 API(/library-api) 응답의 level 값.
     *
     * 소비자인 클라우봇(외부 학습프로그램)의 등급 체계이며, 고래영어 레거시의
     * api/userLogin.php 와 whale/index.php 가 mb_type 을 변환하던 방식을 따른다.
     * 강사(4)는 클라우봇이 신설한 등급이다.
     *
     * 본부장은 레거시 SSO 에서도 차단 대상이라 0 이다.
     * g5_member.mb_level 과는 무관한 값이다.
     */
    public function libraryLevel(): int
    {
        return match ($this) {
            self::Student => 1,
            self::Campus => 2,
            self::Admin => 3,
            self::Teacher => 4,
            default => 0,
        };
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
            5 => self::Teacher,
            default => self::Unknown,
        };
    }
}
