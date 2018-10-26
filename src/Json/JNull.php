<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

final class JNull implements JVal
{
    public function val()
    {
        return null;
    }
}
