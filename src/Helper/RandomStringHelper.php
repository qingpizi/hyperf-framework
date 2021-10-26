<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Helper;


class RandomStringHelper
{
    public static function randomStr($length = 6): string
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $returnStr = '';

        for ($i = 0; $i < $length; $i++) {
            $returnStr .= $pattern[mt_rand(0, 61)];
        }

        return $returnStr;
    }

    public static function randomNumberStr($length = 6): string
    {
        $pattern = '1234567890';
        $returnStr = '';

        for ($i = 0; $i < $length; $i++) {
            $returnStr .= $pattern[mt_rand(0, 9)];
        }

        return $returnStr;
    }

    public static function randomAlphaStr($length = 6): string
    {
        $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $returnStr = '';

        for ($i = 0; $i < $length; $i++) {
            $returnStr .= $pattern[mt_rand(0, 51)];
        }

        return $returnStr;
    }
}
