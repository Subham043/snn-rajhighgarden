<?php

namespace App\Enums;

enum GenderEnum:string {
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
    case OTHER = 'OTHER';

    public function label(): string {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string {
        return match ($value) {
            GenderEnum::MALE => 'MALE',
            GenderEnum::FEMALE => 'FEMALE',
            GenderEnum::OTHER => 'OTHER',
        };
    }

}
