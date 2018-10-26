<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Json\JBool;
use Pwm\JGami\Json\JNull;
use Pwm\JGami\Json\JVal;

/**
 * @group Tree
 */
final class NodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new Node(new NodeKey('key'), new NodePath('path'), new JNull());

        self::assertInstanceOf(Node::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertInstanceOf(JVal::class, $node->jVal());
        self::assertNull($node->jVal()->val());
    }

    /**
     * @test
     */
    public function it_creates_with_jnull_as_default(): void
    {
        $node = new Node(new NodeKey('key'), new NodePath('path'));

        self::assertInstanceOf(JNull::class, $node->jVal());
        self::assertNull($node->jVal()->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_node(): void
    {
        $node1 = new Node(new NodeKey('key'), new NodePath('path'), new JNull());
        $node2 = Node::from($node1, new JBool(true));

        self::assertInstanceOf(Node::class, $node2);
        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertInstanceOf(JBool::class, $node2->jVal());
        self::assertTrue($node2->jVal()->val());
    }
}
