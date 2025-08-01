<?php

namespace Ledc\SupportSdk;

use JsonSerializable;

/**
 * 参数抽象类
 */
abstract class Parameters implements JsonSerializable
{
    use HasJsonSerializable;

    /**
     * 构造函数
     * @param array $properties
     * @return void
     */
    public function __construct(array $properties = [])
    {
        $this->initProperties($properties);
    }
}
