<?php
declare(strict_types=1);

namespace Pwm\JGami\Node\Json;

use PHPUnit\Framework\TestCase;

final class ObjectNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new ObjectNode('key', 'value');

        self::assertInstanceOf(ObjectNode::class, $node);
        self::assertSame('key', $node->getKey());
        self::assertSame('value', $node->getValue());
    }
}
