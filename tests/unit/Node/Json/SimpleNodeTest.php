<?php
declare(strict_types=1);

namespace Pwm\JGami\Node\Json;

use PHPUnit\Framework\TestCase;

final class SimpleNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new SimpleNode('key', 'value');

        self::assertInstanceOf(SimpleNode::class, $node);
        self::assertSame('key', $node->getKey());
        self::assertSame('value', $node->getValue());
    }
}
