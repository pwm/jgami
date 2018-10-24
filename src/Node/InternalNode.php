<?php
declare(strict_types=1);

namespace Pwm\JGami\Node;

use Pwm\JGami\Node\Json\ArrayNode;
use Pwm\JGami\Node\Json\JsonNode;
use Pwm\JGami\Node\Json\ObjectNode;

final class InternalNode implements TreeNode
{
    /** @var JsonNode */
    private $jsonNode;

    public static function arrayNode(ArrayNode $jsonNode): self
    {
        return new self($jsonNode);
    }

    public static function objectNode(ObjectNode $jsonNode): self
    {
        return new self($jsonNode);
    }

    public function getJsonNode(): JsonNode
    {
        return $this->jsonNode;
    }

    private function __construct(JsonNode $jsonNode)
    {
        $this->jsonNode = $jsonNode;
    }
}
