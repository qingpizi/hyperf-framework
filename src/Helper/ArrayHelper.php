<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Helper;

class ArrayHelper
{
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param array|object $target
     * @param null $key
     * @param null|mixed $default
     * @return array|mixed
     */
    public static function get($target, $key = null, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', is_int($key) ? (string)$key : $key);

        while (!is_null($segment = array_shift($key))) {
            if (self::isArray($target) && isset($target[$segment])) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return $default;
            }
        }
        return $target;
    }

    /**
     * @param array|object|null $target 目标数组
     * @param array | string | null $key 键值，支持点分方式 ep: db.host
     * @param mixed $value 值
     * @param bool $overwrite 是否覆盖原有的值
     * @return array|null
     */
    public static function set( &$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', is_int($key) ? (string)$key : $key);
        $segment = array_shift($segments);

        if (self::isArray($target)) {
            if ($segments) {
                if (!isset($target[$segment])) {
                    $target[$segment] = [];
                }
                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || !isset($target[$segment])) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }
                self::set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];
            if ($segments) {
                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }
        return $target;
    }

    /**
     * @param array|object|null $target 目标数组
     * @param array | string | int | null $key 键值，支持点分方式 ep: db.host
     * @return array|mixed|null
     */
    public static function has($target, $key)
    {
        if (is_null($key)) {
            return false;
        }

        $key = is_array($key) ? $key : explode('.', is_int($key) ? (string)$key : $key);
        while (!is_null($segment = array_shift($key))) {
            if (self::isArray($target) && isset($target[$segment])) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return false;
            }
        }
        return true;
    }

    public static function isArray($obj): bool
    {
        return is_array($obj) || $obj instanceof \ArrayAccess;
    }

    public static function arrayRemove(array $arr, $value): array
    {
        $keys = array_keys($arr, $value);
        if(!empty($keys)){
            foreach ($keys as $key) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }
}
