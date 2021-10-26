<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Helper;


class IDCardHelper
{
    /**
     * 性别男
     */
    const MAN = 1;

    /**
     * 性别女
     */
    const GIRL = 2;

    /**
     * 的确代号
     * @var array|string[]
     */
    public static array $area = [
        11 => "北京",  12 => "天津", 13 => "河北",   14 => "山西", 15 => "内蒙古", 21 => "辽宁",
        22 => "吉林",  23 => "黑龙江", 31 => "上海",  32 => "江苏",  33 => "浙江", 34 => "安徽",
        35 => "福建",  36 => "江西", 37 => "山东", 41 => "河南", 42 => "湖北",  43 => "湖南",
        44 => "广东", 45 => "广西",  46 => "海南", 50 => "重庆", 51 => "四川", 52 => "贵州",
        53 => "云南", 54 => "西藏", 61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏",
        65 => "新疆", 71 => "台湾", 81 => "香港", 82 => "澳门", 91 => "国外"
    ];

    /**
     * 检证身份证是否正确
     * @param string $IDCard
     * @return bool
     */
    public static function isIDCard(string $IDCard): bool
    {
        $IDCard = self::to18IDCard($IDCard);
        if (! is_string($IDCard)) {
            return false;
        }
        $IDCardBase = substr($IDCard, 0, 17);
        return (self::getVerifyNum($IDCardBase) == strtoupper(substr($IDCard, 17, 1)));
    }

    /**
     * 获取身份证生日
     * @param $IDCard
     * @return false|string
     */
    public static function getBirthday($IDCard)
    {
        $IDCard = self::to18IDCard($IDCard);
        if (! is_string($IDCard)) {
            return false;
        }

        $birthday = substr($IDCard, 6, 8);
        return date('Y-m-d', strtotime($birthday));
    }

    /**
     * 获取身份证性别
     * @param $IDCard
     * @return false|int
     */
    public static function getGender($IDCard)
    {
        $IDCard = self::to18IDCard($IDCard);
        if (! is_string($IDCard)) {
            return false;
        }
        return (int) substr($IDCard, -2, 1) % 2 ? self::MAN : self::GIRL;
    }

    /**
     * 获取身份证对应的省、自治区、直辖市代
     * @param $IDCard
     * @return false|string
     */
    public static function getArea($IDCard){
        $IDCard = self::to18IDCard($IDCard);
        if (! is_string($IDCard)) {
            return false;
        }
        $index = substr($IDCard,0,2);
        return self::$area[$index] ?? false;
    }

    /**
     * 格式化15位身份证号码为18位
     * @param $IDCard
     * @return false|string
     */
    private static function to18IDCard($IDCard)
    {
        $IDCard = trim($IDCard);
        if (strlen($IDCard) == 18) {
            return $IDCard;
        }
        if (strlen($IDCard) != 15) {
            return false;
        }
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
            $IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
        } else {
            $IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
        }
        $verifyNum = self::getVerifyNum($IDCard);
        if (! is_string($verifyNum)) {
            return false;
        }
        return $IDCard . $verifyNum;
    }

    /**
     * 计算身份证校验码，根据国家标准gb 11643-1999
     * @param $IDCardBase
     * @return false|string
     */
    private static function getVerifyNum($IDCardBase)
    {
        if (! is_string($IDCardBase) || strlen($IDCardBase) != 17) {
            return false;
        }
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verifyNumberList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($IDCardBase); $i++) {
            $checksum += substr($IDCardBase, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        return $verifyNumberList[$mod];
    }

}