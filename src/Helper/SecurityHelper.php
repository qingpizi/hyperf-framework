<?php


namespace Qingpizi\HyperfFramework\Helper;


class SecurityHelper
{

    const AES_128_CBC = 'AES-128-CBC';

    public static function encrypt($message, $secret_key, $cipher)
    {
        $iv = md5(time() . uniqid(), true);
        $raw = openssl_encrypt(
            $message,
            $cipher,
            $secret_key,
            OPENSSL_RAW_DATA,
            $iv
        );
        return base64_encode($raw);
    }

    public static function decrypt($message, $secret_key, $cipher)
    {
        $iv = md5(time() . uniqid(), true);
        return openssl_decrypt(
            base64_decode($message),
            $cipher,
            $secret_key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public static function signMD5WithRSA(string $private_key, $data)
    {
        if (empty($private_key) || empty($data)) {
            return false;
        }

        $pkeyid = openssl_get_privatekey($private_key);
        if (empty($pkeyid))
        {
            return false;
        }
        $result = openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_MD5);
        if (!$result) {
            return false;
        }
        openssl_free_key($pkeyid);
        return base64_encode($signature);
    }

    public static function verifyMD5WithRSA(string $public_key, $data, $signature)
    {
        if (empty($public_key) || empty($data) || empty($signature)) {
            return false;
        }

        $pkeyid = openssl_get_publickey($public_key);
        if (empty($pkeyid))
        {
            return false;
        }

        $ret = openssl_verify($data, base64_decode($signature), $pkeyid, OPENSSL_ALGO_MD5);
        return $ret == 1;
    }
}
