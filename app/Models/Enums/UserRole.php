<?php

namespace App\Models\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Admin = 'admin';

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
