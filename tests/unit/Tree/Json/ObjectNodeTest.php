<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;
use stdClass;

final class ObjectNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $c = new stdClass();
        $c->foo = 'bar';

        $node = new ObjectNode(new NodeKey('key'), new NodePath('path'), $c);

        self::assertInstanceOf(ObjectNode::class, $node);
        self::assertSame('key', $node->key()->val());
        self::assertSame('path', $node->path()->val());
        self::assertEquals($c, $node->val());
    }

    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $c1 = new stdClass();
        $c1->foo = 'X';
        $c2 = new stdClass();
        $c2->foo = 'Y';

        $node1 = new ObjectNode(new NodeKey('key'), new NodePath('path'), $c1);
        $node2 = ObjectNode::from($node1, $c2);

        self::assertTrue($node2->key()->eq($node1->key()->val()));
        self::assertTrue($node2->path()->eq($node1->path()->val()));
        self::assertEquals($c2, $node2->val());
    }
}
