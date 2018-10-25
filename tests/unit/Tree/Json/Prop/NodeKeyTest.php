<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json\Prop;

use PHPUnit\Framework\TestCase;

final class NodeKeyTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $key = new NodeKey('key');

        self::assertInstanceOf(NodeKey::class, $key);
        self::assertSame('key', $key->val());
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function it_throws_on_invalid_type(): void
    {
        new NodeKey(null);
    }

    /**
     * @test
     */
    public function it_has_equality(): void
    {
        self::assertTrue((new NodeKey('foo'))->eq('foo'));
        self::assertFalse((new NodeKey('foo'))->ne('foo'));

        self::assertFalse((new NodeKey('foo'))->eq('bar'));
        self::assertTrue((new NodeKey('foo'))->ne('bar'));
    }
}
