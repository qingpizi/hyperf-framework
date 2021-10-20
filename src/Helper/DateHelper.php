<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Helper;


use DateTime;

class DateHelper
{
    static public function getTodayZero()
    {
        return strtotime(date('Y-m-d 00:00:00', time()));
    }

    /**
     * @param $time
     * @return string
     * @throws \Exception
     */
    static public function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    /**
     * 检测是否是时间戳
     */
    public static function isTimestamp($timestamp) {
        if(!is_int($timestamp)) {
            return false;
        }

        if(strtotime(date('Y-m-d H:i:s',$timestamp)) === $timestamp) {
            return true;
        } else {
            return false;
        }
    }
}
