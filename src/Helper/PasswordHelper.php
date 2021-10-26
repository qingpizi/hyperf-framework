<?php


namespace Qingpizi\HyperfFramework\Helper;


class PasswordHelper
{
    public static function generatePassword($originPassword)
    {
        return password_hash($originPassword, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);
    }

    public static function verifyPassword($originPassword, $hashedPassword): bool
    {
        return password_verify($originPassword, $hashedPassword);
    }
}
