<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class ArrayNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new ArrayNode(new NodeKey('key'), new NodePath('path'), ['foo']);

        self::assertInstanceOf(ArrayNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertSame(['foo'], $node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $node1 = new ArrayNode(new NodeKey('key'), new NodePath('path'), ['foo']);
        $node2 = ArrayNode::from($node1, ['bar']);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertSame(['bar'], $node2->val());
    }
}
