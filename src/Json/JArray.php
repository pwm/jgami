<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JArray implements JVal
{
    /** @var array */
    private $val;

    public function __construct(array $val)
    {
        $this->val = $val;
    }

    public function val(): array
    {
        return $this->val;
    }
}
