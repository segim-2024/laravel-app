<?php

namespace App\Enums;

enum WhaleLearningPlatformEnum: string
{
    case Win64 = 'win64';
    case MacIntel = 'mac_intel';
    case MacArm = 'mac_arm';

    public function getS3Path(): string
    {
        return match ($this) {
            self::Win64 => 'whale-learning/1.0.0/Whale Learning Setup 1.0.0.exe',
            self::MacIntel => 'whale-learning/1.0.0/Whale Learning-1.0.0.dmg',
            self::MacArm => 'whale-learning/1.0.0/Whale Learning-1.0.0-arm64.dmg',
        };
    }
}
