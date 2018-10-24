<?php
declare(strict_types=1);

namespace Pwm\JGami\Node\Json;

final class ArrayNode implements JsonNode
{
    /** @var int|string */
    private $key;
    /** @var null|mixed */
    private $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}
