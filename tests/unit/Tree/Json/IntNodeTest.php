<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class IntNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new IntNode(new NodeKey('key'), new NodePath('path'), 1234);

        self::assertInstanceOf(IntNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertSame(1234, $node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $node1 = new IntNode(new NodeKey('key'), new NodePath('path'), 1234);
        $node2 = IntNode::from($node1, 5678);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertSame(5678, $node2->val());
    }
}
