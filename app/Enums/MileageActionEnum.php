<?php

namespace App\Enums;

enum MileageActionEnum: string
{
    case Accrue = 'ACCRUE';     // 적립
    case Use = 'USE';           // 사용
    case Convert = 'CONVERT';   // 포인트 전환
    case Adjust = 'ADJUST';     // 관리자 조정

    public function label(): string
    {
        return match($this) {
            self::Accrue => '적립',
            self::Use => '사용',
            self::Convert => '전환',
            self::Adjust => '조정',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Accrue => 'icn_mileage_save.png',
            self::Use => 'icn_mileage_use.png',
            self::Convert => 'icn_mileage_convert.png',
            self::Adjust => 'icn_mileage_save.png',
        };
    }

    public function amountClass(): string
    {
        return match($this) {
            self::Accrue => 'blue',
            self::Use => 'red',
            self::Convert => '',
            self::Adjust => '',
        };
    }
}