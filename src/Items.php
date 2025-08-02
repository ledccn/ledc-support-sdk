<?php

namespace Ledc\SupportSdk;

use ArrayObject;
use InvalidArgumentException;
use JsonSerializable;

/**
 * 数组对象
 */
class Items extends ArrayObject implements JsonSerializable
{
    /**
     * 转换成数组
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_map(fn($value) => $value instanceof JsonSerializable ? $value->jsonSerialize() : $value, $this->getArrayCopy());
    }

    /**
     * 转换成数组
     * @return array
     */
    final public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * 转字符串
     * @param int $options
     * @return string
     */
    final public function toJson(int $options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }
        return $json;
    }
}