<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json\Prop;

use function array_merge;
use function array_reduce;
use function strpos;

final class NodePath
{
    /** @var string */
    private $val;

    public function __construct(string $val)
    {
        $this->val = $val;
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

    public function hasAll(string $fragment, string ...$fragments): bool
    {
        return array_reduce(array_merge([$fragment], $fragments), function (bool $acc, string $val): bool {
            return $acc && strpos($this->val(), $val) !== false;
        }, true);
    }

    public function hasAny(string $fragment, string ...$fragments): bool
    {
        return array_reduce(array_merge([$fragment], $fragments), function (bool $acc, string $val): bool {
            return $acc || strpos($this->val(), $val) !== false;
        }, false);
    }

    public function hasNone(string $fragment, string ...$fragments): bool
    {
        return array_reduce(array_merge([$fragment], $fragments), function (bool $acc, string $val): bool {
            return $acc && strpos($this->val(), $val) === false;
        }, true);
    }
}
