<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Helper;


class GeoHelper
{
    /**
     * 根据两点间的经纬度计算距离
     * @param array $from
     * @param array $to
     * @return float
     */
    public static function getDistance(array $from, array $to)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
          Convert these degrees to radians
          to work with the formula
        */

        $lat1 = ($from['lat'] * pi() ) / 180;
        $lng1 = ($from['lng'] * pi() ) / 180;

        $lat2 = ($to['lat'] * pi() ) / 180;
        $lng2 = ($to['lng'] * pi() ) / 180;

        /*
          Using the
          Haversine formula

          http://en.wikipedia.org/wiki/Haversine_formula

          calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }
}
