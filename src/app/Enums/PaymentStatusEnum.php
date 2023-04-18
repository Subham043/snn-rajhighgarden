<?php

namespace App\Enums;

enum PaymentStatusEnum:Int {
    case PENDING = 0;
    case COMPLETED = 1;

    public function label(): int {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): int {
        return match ($value) {
            PaymentStatusEnum::PENDING => 0,
            PaymentStatusEnum::COMPLETED => 1,
        };
    }

    public static function getValue(Int $value): string {
        return match ($value) {
            0 => "PENDING",
            1 => "COMPLETED",
        };
    }
}
