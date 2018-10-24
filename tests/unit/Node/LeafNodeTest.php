<?php
declare(strict_types=1);

namespace Pwm\JGami\Node;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Node\Json\ArrayNode;
use Pwm\JGami\Node\Json\ObjectNode;
use Pwm\JGami\Node\Json\SimpleNode;

final class LeafNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_array_node(): void
    {
        $node = LeafNode::arrayNode(new ArrayNode('key', 'value'));

        self::assertInstanceOf(LeafNode::class, $node);
        self::assertInstanceOf(ArrayNode::class, $node->getJsonNode());
    }

    /**
     * @test
     */
    public function it_creates_from_object_node(): void
    {
        $node = LeafNode::objectNode(new ObjectNode('key', 'value'));

        self::assertInstanceOf(LeafNode::class, $node);
        self::assertInstanceOf(ObjectNode::class, $node->getJsonNode());
    }

    /**
     * @test
     */
    public function it_creates_from_simple_node(): void
    {
        $node = LeafNode::simpleNode(new SimpleNode('key', 'value'));

        self::assertInstanceOf(LeafNode::class, $node);
        self::assertInstanceOf(SimpleNode::class, $node->getJsonNode());
    }
}
