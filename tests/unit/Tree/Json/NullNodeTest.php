<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class NullNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new NullNode(new NodeKey('key'), new NodePath('path'));

        self::assertInstanceOf(NullNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertNull($node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $node1 = new NullNode(new NodeKey('key'), new NodePath('path'));
        $node2 = NullNode::from($node1);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertNull($node2->val());
    }
}
