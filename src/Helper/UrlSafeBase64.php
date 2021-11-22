<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Helper;

class UrlSafeBase64
{
    /**
     * URL安全的字符串加码
     *
     * @param string $data 要加码
     *
     * @return string
     *
     */
    public static function encode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }

    /**
     * URL安全的字符串解码
     *
     * @param string $data 要解码
     *
     * @return string
     *
     */
    public static function decode(string $data): string
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return $data;
    }
}
