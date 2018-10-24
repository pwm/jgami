<?php
declare(strict_types=1);

namespace Pwm\JGami\Node\Json;

use PHPUnit\Framework\TestCase;

final class ArrayNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $node = new ArrayNode('key', 'value');

        self::assertInstanceOf(ArrayNode::class, $node);
        self::assertSame('key', $node->getKey());
        self::assertSame('value', $node->getValue());
    }
}
