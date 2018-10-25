<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class FloatNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_another_json_node(): void
    {
        $tplNode = new NullNode(new NodeKey('key'), new NodePath('path'));
        $node = FloatNode::from($tplNode, 24.68);

        self::assertTrue($node->key()->eq($tplNode->key()->val()));
        self::assertTrue($node->path()->eq($tplNode->path()->val()));
        self::assertSame(24.68, $node->val());
    }
}
