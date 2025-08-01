<?php

namespace Ledc\SupportSdk;

/**
 * 工具类
 */
class Utils
{
    /**
     * 过滤空参数
     * @param array $params
     * @return array
     */
    final public static function filter(array $params): array
    {
        return array_filter($params, fn($value) => null !== $value && '' !== $value && [] !== $value);
    }
}
