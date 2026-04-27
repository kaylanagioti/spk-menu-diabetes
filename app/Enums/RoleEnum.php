<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN     = 'admin';
    case ORANG_TUA = 'orang_tua';

    public function label(): string
    {
        return match($this) {
            RoleEnum::ADMIN     => 'Administrator',
            RoleEnum::ORANG_TUA => 'Orang Tua',
        };
    }
}
