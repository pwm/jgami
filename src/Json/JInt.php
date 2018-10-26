<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JInt implements JVal
{
    /** @var int */
    private $val;

    public function __construct(int $val)
    {
        $this->val = $val;
    }

    public function val(): int
    {
        return $this->val;
    }
}
