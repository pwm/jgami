<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use stdClass;

final class JObject implements JVal
{
    /** @var stdClass */
    private $val;

    public function __construct(stdClass $val)
    {
        $this->val = $val;
    }

    public function val(): stdClass
    {
        return $this->val;
    }
}
