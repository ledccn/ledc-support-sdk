<?php

namespace Ledc\SupportSdk;

use InvalidArgumentException;
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
}
