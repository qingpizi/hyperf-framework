<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Helper;


class MobileHelper
{
    CONST REGEX_MOBILE_EXACT = "/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/";

    public static function isMobile(string $mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        if (strpos($mobile, '+86') === 0) {
            $mobile = substr($mobile, 3);
        }
        return preg_match(self::REGEX_MOBILE_EXACT, $mobile);
    }

    public static function hideMobile($str){
        return substr_replace($str,'****',3,4);
    }

}
