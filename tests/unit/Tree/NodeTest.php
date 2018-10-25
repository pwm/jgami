<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\ArrayNode;
use Pwm\JGami\Tree\Json\BoolNode;
use Pwm\JGami\Tree\Json\FloatNode;
use Pwm\JGami\Tree\Json\IntNode;
use Pwm\JGami\Tree\Json\NullNode;
use Pwm\JGami\Tree\Json\ObjectNode;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;
use Pwm\JGami\Tree\Json\StringNode;
use stdClass;

final class NodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_internal_object_node(): void
    {
        $node = Node::internalObject(new ObjectNode(new NodeKey('key'), new NodePath('path'), new stdClass()));

        self::assertInstanceOf(Node::class, $node);
        self::assertInstanceOf(ObjectNode::class, $node->getJsonNode());
        self::assertTrue($node->isInternal());
    }

    /**
     * @test
     */
    public function it_creates_from_internal_array_node(): void
    {
        $node = Node::internalArray(new ArrayNode(new NodeKey('key'), new NodePath('path'), []));

        self::assertInstanceOf(Node::class, $node);
        self::assertInstanceOf(ArrayNode::class, $node->getJsonNode());
        self::assertTrue($node->isInternal());
    }

    /**
     * @test
     */
    public function it_creates_from_leaf_json_node(): void
    {
        $nullNode = Node::leaf(new NullNode(new NodeKey('key'), new NodePath('path')));
        $boolNode = Node::leaf(new BoolNode(new NodeKey('key'), new NodePath('path'), true));
        $intNode = Node::leaf(new IntNode(new NodeKey('key'), new NodePath('path'), 1234));
        $floatNode = Node::leaf(new FloatNode(new NodeKey('key'), new NodePath('path'), 12.34));
        $stringNode = Node::leaf(new StringNode(new NodeKey('key'), new NodePath('path'), 'val'));

        self::assertInstanceOf(Node::class, $nullNode);
        self::assertInstanceOf(Node::class, $boolNode);
        self::assertInstanceOf(Node::class, $intNode);
        self::assertInstanceOf(Node::class, $floatNode);
        self::assertInstanceOf(Node::class, $stringNode);

        self::assertInstanceOf(NullNode::class, $nullNode->getJsonNode());
        self::assertInstanceOf(BoolNode::class, $boolNode->getJsonNode());
        self::assertInstanceOf(IntNode::class, $intNode->getJsonNode());
        self::assertInstanceOf(FloatNode::class, $floatNode->getJsonNode());
        self::assertInstanceOf(StringNode::class, $stringNode->getJsonNode());

        self::assertFalse($nullNode->isInternal());
        self::assertFalse($boolNode->isInternal());
        self::assertFalse($intNode->isInternal());
        self::assertFalse($floatNode->isInternal());
        self::assertFalse($stringNode->isInternal());
    }
}
