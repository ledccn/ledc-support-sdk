<?php

namespace Ledc\SupportSdk;

/**
 * 启用与禁用枚举类
 */
class EnabledEnums extends EnumsInterface
{
    /**
     * 启用
     */
    public const YES = 1;
    /**
     * 禁止
     */
    public const NO = 0;

    /**
     * 枚举说明列表
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::YES => '启用',
            self::NO => '禁止',
        ];
    }
}
