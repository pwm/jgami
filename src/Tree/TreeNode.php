<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use Pwm\JGami\Tree\Json\ArrayNode;
use Pwm\JGami\Tree\Json\JsonNode;
use Pwm\JGami\Tree\Json\ObjectNode;

final class TreeNode
{
    public const INTERNAL = 'Internal';
    public const LEAF     = 'Leaf';

    /** @var string */
    private $tag;
    /** @var JsonNode */
    private $jsonNode;

    public static function internalObject(ObjectNode $jsonNode): self
    {
        return new self(self::INTERNAL, $jsonNode);
    }

    public static function internalArray(ArrayNode $jsonNode): self
    {
        return new self(self::INTERNAL, $jsonNode);
    }

    public static function leaf(JsonNode $jsonNode): self
    {
        return new self(self::LEAF, $jsonNode);
    }

    public function isInternal(): bool
    {
        return $this->tag === self::INTERNAL;
    }

    public function jsonNode(): JsonNode
    {
        return $this->jsonNode;
    }

    private function __construct(string $tag, JsonNode $jsonNode)
    {
        $this->tag = $tag;
        $this->jsonNode = $jsonNode;
    }
}
