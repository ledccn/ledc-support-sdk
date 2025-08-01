<?php

namespace Ledc\SupportSdk;

/**
 * 枚举接口
 */
abstract class EnumsInterface
{
    /**
     * 枚举说明列表
     * @return string[]
     */
    abstract public static function cases(): array;

    /**
     * 获取枚举值的描述
     * @param int|string $value
     * @return string
     */
    final public static function getLabel($value): string
    {
        return static::cases()[$value] ?? '';
    }

    /**
     * 验证枚举值是否有效
     * @param int|string $value
     * @return bool
     */
    final public static function isValid($value): bool
    {
        return array_key_exists($value, static::cases());
    }

    /**
     * 枚举列表
     * @return array
     */
    public static function list(): array
    {
        $rs = [];
        foreach (static::cases() as $value => $name) {
            $rs[] = compact('name', 'value');
        }
        return $rs;
    }
}
