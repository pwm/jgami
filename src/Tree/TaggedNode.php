<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

final class TaggedNode
{
    public const INTERNAL = 'Internal';
    public const LEAF     = 'Leaf';

    /** @var string */
    private $tag;
    /** @var Node */
    private $node;

    public static function internal(Node $node): self
    {
        return new self(self::INTERNAL, $node);
    }

    public static function leaf(Node $node): self
    {
        return new self(self::LEAF, $node);
    }

    public function isInternal(): bool
    {
        return $this->tag === self::INTERNAL;
    }

    public function node(): Node
    {
        return $this->node;
    }

    private function __construct(string $tag, Node $node)
    {
        $this->tag = $tag;
        $this->node = $node;
    }
}
