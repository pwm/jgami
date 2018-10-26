<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;
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
    }
}
