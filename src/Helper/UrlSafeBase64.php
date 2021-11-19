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
    private static function urlSafeBase64Encode(string $data): string
    {
        $data = base64_encode($data);
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
    private static function urlSafeBase64Decode(string $data): string
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}
