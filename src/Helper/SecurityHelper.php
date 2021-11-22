<?php


namespace Qingpizi\HyperfFramework\Helper;


class SecurityHelper
{

    const AES_256_CBC = 'AES-256-CBC';

    public static function encrypt($message, $secret_key, $iv = null, $cipher = self::AES_256_CBC)
    {
        $raw = openssl_encrypt(
            $message,
            $cipher,
            $secret_key,
            OPENSSL_RAW_DATA,
            is_null($iv) ? md5(time() . uniqid(), true) : $iv,
        );
        return base64_encode($raw);
    }

    public static function decrypt($message, $secret_key, $iv = null, $cipher = self::AES_256_CBC)
    {
        return openssl_decrypt(
            base64_decode($message),
            $cipher,
            $secret_key,
            OPENSSL_RAW_DATA,
            is_null($iv) ? md5(time() . uniqid(), true) : $iv,
        );
    }

    public static function signMD5WithRSA(string $private_key, $data)
    {
        if (empty($private_key) || empty($data)) {
            return false;
        }

        $pkeyId = openssl_get_privatekey($private_key);
        if (empty($pkeyId))
        {
            return false;
        }
        $result = openssl_sign($data, $signature, $pkeyId, OPENSSL_ALGO_MD5);
        if (!$result) {
            return false;
        }
        openssl_free_key($pkeyId);
        return base64_encode($signature);
    }

    public static function verifyMD5WithRSA(string $public_key, $data, $signature): bool
    {
        if (empty($public_key) || empty($data) || empty($signature)) {
            return false;
        }

        $pkeyId = openssl_get_publickey($public_key);
        if (empty($pkeyId))
        {
            return false;
        }

        $ret = openssl_verify($data, base64_decode($signature), $pkeyId, OPENSSL_ALGO_MD5);
        return $ret == 1;
    }
}
