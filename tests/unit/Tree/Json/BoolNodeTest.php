<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class BoolNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new BoolNode(new NodeKey('key'), new NodePath('path'), true);

        self::assertInstanceOf(BoolNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertTrue($node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $node1 = new BoolNode(new NodeKey('key'), new NodePath('path'), true);
        $node2 = BoolNode::from($node1, false);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertFalse($node2->val());
    }
}
