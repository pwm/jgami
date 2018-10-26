<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JFloat implements JVal
{
    /** @var float */
    private $val;

    public function __construct(float $val)
    {
        $this->val = $val;
    }

    public function val(): float
    {
        return $this->val;
    }
}
