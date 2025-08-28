<?php

namespace Ledc\SupportSdk;

use InvalidArgumentException;
use LogicException;
use Throwable;

/**
 * 工具类
 */
final class Utils
{
    /**
     * 过滤空参数
     * @param array $params
     * @return array
     */
    public static function filter(array $params): array
    {
        return array_filter($params, fn($value) => null !== $value && '' !== $value && [] !== $value);
    }

    /**
     * 生成UUID4
     * @return string
     */
    public static function generateUUID4(): string
    {
        try {
            $data = random_bytes(16);
        } catch (Throwable $throwable) {
            if (!function_exists('openssl_random_pseudo_bytes')) {
                throw new InvalidArgumentException('Cannot generate random bytes');
            }
            $data = openssl_random_pseudo_bytes(16);
        }

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // 设置版本为4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // 设置变体为RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * 生成20位纯数字订单号
     * - 规则：年月日时分秒 + 6位微秒数（示例值20241101235959123456）
     * @return string
     */
    public static function generateOrderNumber(): string
    {
        [$mSec, $timestamp] = explode(' ', microtime());
        return date('YmdHis', (int)$timestamp) . substr($mSec, 2, 6);
    }

    /**
     * 生成18位纯数字订单号
     * - 规则：年月日时分秒 + 4位微秒数（示例值202411012359591234）
     * @return string
     */
    public static function generateOrderSn(): string
    {
        [$timestamp, $mSec] = explode('.', microtime(true));
        return date('YmdHis', (int)$timestamp) . str_pad($mSec, 4, '0');
    }

    /**
     * 生成唯一随机字符串（基于时间戳和随机数）
     * - 可以作为SessionId或唯一标识符
     * @param int $random_bytes_length 随机字节数的长度
     * @return string
     */
    public static function generateRandom(int $random_bytes_length = 8): string
    {
        $length = max(8, $random_bytes_length);
        try {
            return bin2hex(pack('d', microtime(true)) . random_bytes($length));
        } catch (Throwable $e) {
            if (function_exists('openssl_random_pseudo_bytes')) {
                return bin2hex(pack('d', microtime(true)) . openssl_random_pseudo_bytes($length));
            }
            throw new LogicException('Cannot generate random bytes, ' . $e->getMessage());
        }
    }

    /**
     * 获取当前毫秒时间戳
     */
    public static function getMilliTime(): int
    {
        return (int)(microtime(true) * 1000);
    }

    /**
     * 转JSON
     * @param mixed $value
     * @param int $options
     * @return string
     */
    public static function toJson($value, int $options = 0): string
    {
        $json = json_encode($value, $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * 根据起点坐标和终点坐标测算距离
     * @param array $from 起点经纬度
     * @param array $to 终点经纬度
     * @param bool $km 是否以公里为单位（false米、true千米）
     * @param int $decimal 精度
     * @return float 直线距离数值
     */
    public static function getDistanceGeo(array $from, array $to, bool $km = true, int $decimal = 2): float
    {
        sort($from);
        sort($to);
        $earth_radius = 6370.996;   // 地球半径系数

        $unit = $km ? 1 : 1000;
        $distance = $earth_radius * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * $unit;

        return round($distance, $decimal);
    }
}
