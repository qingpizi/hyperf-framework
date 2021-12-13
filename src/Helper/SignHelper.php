<?php


namespace Qingpizi\HyperfFramework\Helper;


class SignHelper
{
    /**
     * 生产签名
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function makeSign(array $data, string $key = ''): string
    {
        $data = self::filteringParams($data);
        ksort($data);
        $queryString = http_build_query($data);
        return md5($queryString. '&' . $key);
    }

    /**
     * 验证签名
     * @param string $sign
     * @param array $data
     * @param string $key
     * @return bool
     */
    public static function verifySign(string $sign, array $data, string $key = ''): bool
    {
        $data = self::filteringParams($data);
        ksort($data);
        $queryString = http_build_query($data);
        $realSign = md5($queryString. '&' . $key);
        return strcmp($sign, $realSign) === 0;
    }

    /**
     * 过滤参数
     * @param array $data
     * @return array
     */
    private static function filteringParams(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($value === '' || is_null($value) ) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
