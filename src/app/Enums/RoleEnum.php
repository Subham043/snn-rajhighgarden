<?php

namespace App\Enums;

enum RoleEnum:Int {
    case ADMIN = 1;
    case USER = 2;
    case VOLUNTEER = 3;

    public function label(): int {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): int {
        return match ($value) {
            RoleEnum::ADMIN => 1,
            RoleEnum::USER => 2,
            RoleEnum::VOLUNTEER => 3,
        };
    }

    public static function getValue(Int $value): string {
        return match ($value) {
            1 => 'ADMIN',
            2 => 'USER',
            3 => 'VOLUNTEER',
        };
    }
}
