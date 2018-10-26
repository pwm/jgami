<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use TypeError;
use function is_int;
use function is_string;

final class NodeKey
{
    /** @var int|string */
    private $val;

    public function __construct($val)
    {
        if (! is_int($val) && ! is_string($val)) {
            throw new TypeError(sprintf('%s is not a valid NodeKey.', $val));
        }

        $this->val = $val;
    }

    public function val()
    {
        return $this->val;
    }

    public function eq($val): bool
    {
        return $this->val() === $val;
    }

    public function ne($val): bool
    {
        return ! $this->eq($val);
    }
}
