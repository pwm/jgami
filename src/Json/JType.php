<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use stdClass;
use TypeError;
use function is_array;
use function is_bool;
use function is_int;
use function is_float;
use function is_string;

final class JType
{
    public const NULL   = 'null';
    public const BOOL   = 'bool';
    public const INT    = 'int';
    public const FLOAT  = 'float';
    public const STRING = 'string';
    public const OBJECT = 'object';
    public const ARRAY  = 'array';

    /** @var string */
    private $val;

    public static function fromVal($val): self
    {
        if ($val === null) {
            $type = self::NULL;
        } elseif (is_bool($val)) {
            $type = self::BOOL;
        } elseif (is_int($val)) {
            $type = self::INT;
        } elseif (is_float($val)) {
            $type = self::FLOAT;
        } elseif (is_string($val)) {
            $type = self::STRING;
        } elseif ($val instanceof stdClass) {
            $type = self::OBJECT;
        } elseif (is_array($val)) {
            $type = self::ARRAY;
        } else {
            throw new TypeError('Not a valid JType.');
        }
        return new self($type);
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
