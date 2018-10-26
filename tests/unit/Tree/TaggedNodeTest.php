<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;

/**
 * @group Tree
 */
final class TaggedNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_instantiates_as_internal_type(): void
    {
        $internalType = TaggedNode::internal(new Node(new NodeKey('key'), new NodePath('path')));

        self::assertInstanceOf(TaggedNode::class, $internalType);
        self::assertTrue($internalType->isInternal());
        self::assertInstanceOf(Node::class, $internalType->node());
    }

    /**
     * @test
     */
    public function it_instantiates_as_leaf_type(): void
    {
        $leafType = TaggedNode::leaf(new Node(new NodeKey('key'), new NodePath('path')));

        self::assertInstanceOf(TaggedNode::class, $leafType);
        self::assertFalse($leafType->isInternal());
        self::assertInstanceOf(Node::class, $leafType->node());
    }
}
