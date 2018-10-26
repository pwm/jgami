<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;

/**
 * @group Tree
 */
final class NodePathTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $path = new NodePath('path');

        self::assertInstanceOf(NodePath::class, $path);
        self::assertSame('path', $path->val());
    }

    /**
     * @test
     */
    public function it_has_equality(): void
    {
        self::assertTrue((new NodePath('foo'))->eq('foo'));
        self::assertFalse((new NodePath('foo'))->ne('foo'));

        self::assertFalse((new NodePath('foo'))->eq('bar'));
        self::assertTrue((new NodePath('foo'))->ne('bar'));
    }

    /**
     * @test
     */
    public function it_can_match_fragments(): void
    {
        self::assertTrue((new NodePath('foo.bar.baz'))->hasAll('foo'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasAll('xxx'));
        self::assertTrue((new NodePath('foo.bar.baz'))->hasAll('foo', 'bar', 'baz'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasAll('foo', 'bar', 'xxx'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasAll('xxx', 'yyy', 'zzz'));

        self::assertTrue((new NodePath('foo.bar.baz'))->hasAny('foo'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasAny('xxx'));
        self::assertTrue((new NodePath('foo.bar.baz'))->hasAny('foo', 'bar', 'baz'));
        self::assertTrue((new NodePath('foo.bar.baz'))->hasAny('foo', 'bar', 'xxx'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasAny('xxx', 'yyy', 'zzz'));

        self::assertFalse((new NodePath('foo.bar.baz'))->hasNone('foo'));
        self::assertTrue((new NodePath('foo.bar.baz'))->hasNone('xxx'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasNone('foo', 'bar', 'baz'));
        self::assertFalse((new NodePath('foo.bar.baz'))->hasNone('foo', 'bar', 'xxx'));
        self::assertTrue((new NodePath('foo.bar.baz'))->hasNone('xxx', 'yyy', 'zzz'));
    }
}
