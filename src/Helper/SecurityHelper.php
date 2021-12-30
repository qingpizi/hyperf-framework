<?php


namespace Qingpizi\HyperfFramework\Helper;


class SecurityHelper
{

    const AES_256_CBC = 'AES-256-CBC';

    public static function encrypt($message, $secretKey, $iv = '', $cipher = self::AES_256_CBC)
    {
        $raw = openssl_encrypt(
            $message,
            $cipher,
            $secretKey,
            OPENSSL_RAW_DATA,
            $iv,
        );
        return UrlSafeBase64::encode(base64_encode($raw));
    }

    public static function decrypt($message, $secretKey, $iv = '', $cipher = self::AES_256_CBC)
    {
        return openssl_decrypt(
            base64_decode(UrlSafeBase64::decode($message)),
            $cipher,
            $secretKey,
            OPENSSL_RAW_DATA,
            $iv,
        );
    }

    public static function signMD5WithRSA(string $privateKey, $data)
    {
        if (empty($privateKey) || empty($data)) {
            return false;
        }

        $pkeyId = openssl_get_privatekey($privateKey);
        if (empty($pkeyId))
        {
            return false;
        }
        $result = openssl_sign($data, $signature, $pkeyId, OPENSSL_ALGO_MD5);
        if (!$result) {
            return false;
        }
        openssl_free_key($pkeyId);
        return UrlSafeBase64::encode(base64_encode($signature));
    }

    public static function verifyMD5WithRSA(string $publicKey, $data, $signature): bool
    {
        if (empty($publicKey) || empty($data) || empty($signature)) {
            return false;
        }

        $pkeyId = openssl_get_publickey($publicKey);
        if (empty($pkeyId))
        {
            return false;
        }

        $ret = openssl_verify($data, base64_decode(UrlSafeBase64::decode($signature)), $pkeyId, OPENSSL_ALGO_MD5);
        return $ret == 1;
    }
}
