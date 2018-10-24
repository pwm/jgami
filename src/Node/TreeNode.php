<?php
declare(strict_types=1);

namespace Pwm\JGami\Node;

use Pwm\JGami\Node\Json\JsonNode;

interface TreeNode
{
    public function getJsonNode(): JsonNode;
}
