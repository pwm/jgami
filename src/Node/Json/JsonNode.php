<?php
declare(strict_types=1);

namespace Pwm\JGami\Node\Json;

interface JsonNode
{
    public function getKey();

    public function getValue();
}
