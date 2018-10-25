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
    public function it_creates_from_another_json_node(): void
    {
        $c1 = new stdClass();
        $c1->foo = 'X';
        $c2 = new stdClass();
        $c2->foo = 'Y';

        $tplNode = new NullNode(new NodeKey('key'), new NodePath('path'));
        $node = ObjectNode::from($tplNode, $c2);

        self::assertTrue($node->key()->eq($tplNode->key()->val()));
        self::assertTrue($node->path()->eq($tplNode->path()->val()));
        self::assertEquals($c2, $node->val());
    }
}
