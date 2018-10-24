<?php
declare(strict_types=1);

namespace Pwm\JGami\Node;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Node\Json\ArrayNode;
use Pwm\JGami\Node\Json\ObjectNode;

final class InternalNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_array_node(): void
    {
        $node = InternalNode::arrayNode(new ArrayNode('key', 'value'));

        self::assertInstanceOf(InternalNode::class, $node);
        self::assertInstanceOf(ArrayNode::class, $node->getJsonNode());
    }

    /**
     * @test
     */
    public function it_creates_from_object_node(): void
    {
        $node = InternalNode::objectNode(new ObjectNode('key', 'value'));

        self::assertInstanceOf(InternalNode::class, $node);
        self::assertInstanceOf(ObjectNode::class, $node->getJsonNode());
    }
}
