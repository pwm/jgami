<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JString implements JVal
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
}
