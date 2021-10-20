<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Helper;


class StringHelper
{
    public static function mergeSpaces($string)
    {
        return preg_replace("/\s+/", ' ', $string);
    }

    /**
     * @param string $str
     * @param string $suffix
     * @return bool
     */
    public static function endWith(string $str, string $suffix)
    {
        return \substr($str, 0 - \strlen($suffix)) === $suffix;
    }

    /**
     * @param string $str
     * @param string $suffix
     * @return bool
     */
    public static function subEnd(string $str, string $suffix)
    {
        return \substr($str, 0, 0 - \strlen($suffix));
    }
}
