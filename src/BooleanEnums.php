<?php

namespace Ledc\SupportSdk;

/**
 * 布尔枚举
 */
class BooleanEnums extends EnumsInterface
{
    /**
     * 否
     */
    public const FALSE = 0;
    /**
     * 是
     */
    public const TRUE = 1;

    /**
     * 枚举说明列表
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::FALSE => '否',
            self::TRUE => '是',
        ];
    }
}
