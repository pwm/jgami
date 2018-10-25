<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class FloatNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new FloatNode(new NodeKey('key'), new NodePath('path'), 12.34);

        self::assertInstanceOf(FloatNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertSame(12.34, $node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $node1 = new FloatNode(new NodeKey('key'), new NodePath('path'), 12.34);
        $node2 = FloatNode::from($node1, 24.68);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertSame(24.68, $node2->val());
    }
}
