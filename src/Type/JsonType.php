<?php
declare(strict_types=1);

namespace Pwm\JGami\Type;

use stdClass;
use TypeError;
use function is_array;
use function is_bool;
use function is_int;
use function is_float;
use function is_string;

final class JsonType
{
    public const OBJECT = 'object';
    public const ARRAY  = 'array';
    public const BOOL   = 'bool';
    public const INT    = 'int';
    public const FLOAT  = 'float';
    public const STRING = 'string';
    public const NULL   = 'null';

    /** @var string */
    private $val;

    public static function fromVal($val): self
    {
        if ($val instanceof stdClass) {
            return new self(self::OBJECT);
        }
        if (is_array($val)) {
            return new self(self::ARRAY);
        }
        if (is_bool($val)) {
            return new self(self::BOOL);
        }
        if (is_int($val)) {
            return new self(self::INT);
        }
        if (is_float($val)) {
            return new self(self::FLOAT);
        }
        if (is_string($val)) {
            return new self(self::STRING);
        }
        if ($val === null) {
            return new self(self::NULL);
        }
        throw new TypeError('Not a valid NodeType.');
    }

    public function val(): string
    {
        return $this->val;
    }

    public function eq(string $val): bool
    {
        return $this->val() === $val;
    }

    public function ne(string $val): bool
    {
        return ! $this->eq($val);
    }

    private function __construct(string $val)
    {
        $this->val = $val;
    }
}
