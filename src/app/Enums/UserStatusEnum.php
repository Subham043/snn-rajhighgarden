<?php

namespace App\Enums;

enum UserStatusEnum:Int {
    case VERIFICATION_PENDING = 0;
    case ACTIVE = 1;
    case BLOCKED = 2;

    public function label(): int {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): int {
        return match ($value) {
            UserStatusEnum::VERIFICATION_PENDING => 0,
            UserStatusEnum::ACTIVE => 1,
            UserStatusEnum::BLOCKED => 2,
        };
    }

    public static function getValue(Int $value): string {
        return match ($value) {
            0 => "VERIFICATION PENDING",
            1 => "ACTIVE",
            2 => "BLOCKED",
        };
    }
}
