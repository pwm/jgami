<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JBool implements JVal
{
    /** @var bool */
    private $val;

    public function __construct(bool $val)
    {
        $this->val = $val;
    }

    public function val(): bool
    {
        return $this->val;
    }
}
