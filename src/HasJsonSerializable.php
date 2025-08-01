<?php

namespace Ledc\SupportSdk;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Json序列化
 */
trait HasJsonSerializable
{
    /**
     * 获取必填的key
     * @return array
     */
    abstract protected function getRequiredKeys(): array;

    /**
     * 初始化属性
     * @param array $properties
     * @return void
     */
    final protected function initProperties(array $properties): void
    {
        foreach ($properties as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * 转数组
     * @return array
     */
    final public function jsonSerialize(): array
    {
        $items = [];
        $excludesKeys = $this->getExcludesKeys();
        $properties = $this->filterEmptyValues(get_object_vars($this));
        foreach ($properties as $key => $value) {
            // 排除的keys
            if (in_array($key, $excludesKeys, true)) {
                continue;
            }

            if ($value instanceof JsonSerializable) {
                $items[$key] = $value->jsonSerialize();
            } else {
                $items[$key] = $value;
            }
        }

        $this->checkMissingKeys($items);
        $this->validate($items);

        return $items;
    }

    /**
     * 转数组
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

    /**
     * 过滤空值
     * @param array $data
     * @return array
     */
    final public function filterEmptyValues(array $data): array
    {
        return array_filter($data, fn($value) => !is_null($value) && '' !== $value && [] !== $value);
    }

    /**
     * 验证必填参数
     * @param array $properties
     * @return void
     */
    final protected function checkMissingKeys(array $properties): void
    {
        $requiredKeys = $this->getRequiredKeys();
        if (!empty($requiredKeys)) {
            $missingKeys = [];
            foreach ($requiredKeys as $key) {
                if (!isset($properties[$key])) {
                    $missingKeys[] = $key;
                }
            }

            if (!empty($missingKeys)) {
                throw new InvalidArgumentException("缺少必填参数：" . implode(',', $missingKeys));
            }
        }
    }

    /**
     * 验证参数
     * - 验证失败时抛出异常
     * @param array $properties
     * @return void
     */
    protected function validate(array $properties): void
    {
    }

    /**
     * 获取排除的key
     * @return array
     */
    protected function getExcludesKeys(): array
    {
        return [];
    }
}