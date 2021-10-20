<?php


namespace Qingpizi\HyperfFramework\Helper;


class SignHelper
{
    /**
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function makeSign(array $data, $key = '')
    {
        ksort($data);
        $queryString = http_build_query($data);
        $sign = base64_encode(hash_hmac('sha256', $queryString, $key, true));
        return $sign;
    }

    /**
     * @param string $sign
     * @param array $data
     * @param string $key
     * @return bool
     */
    public static function verifySign(string $sign, array $data, $key = '')
    {
        ksort($data);
        $queryString = http_build_query($data);
        $realSign = base64_encode(hash_hmac('sha256', $queryString, $key, true));

        return strtolower($sign) === strtolower($realSign);
    }
}
