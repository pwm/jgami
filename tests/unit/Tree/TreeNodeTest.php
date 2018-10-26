<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Tree\Json\ArrayNode;
use Pwm\JGami\Tree\Json\BoolNode;
use Pwm\JGami\Tree\Json\FloatNode;
use Pwm\JGami\Tree\Json\IntNode;
use Pwm\JGami\Tree\Json\NullNode;
use Pwm\JGami\Tree\Json\ObjectNode;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;
use Pwm\JGami\Tree\Json\StringNode;
use stdClass;

final class TreeNodeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_internal_object_node(): void
    {
        $nullNode = new NullNode(new NodeKey('key'), new NodePath('path'));
        $oTreeNode = TreeNode::internalObject(ObjectNode::from($nullNode, new stdClass()));

        self::assertInstanceOf(TreeNode::class, $oTreeNode);
        self::assertInstanceOf(ObjectNode::class, $oTreeNode->jsonNode());
        self::assertTrue($oTreeNode->isInternal());
    }

    /**
     * @test
     */
    public function it_creates_from_internal_array_node(): void
    {
        $nullNode = new NullNode(new NodeKey('key'), new NodePath('path'));
        $aTreeNode = TreeNode::internalArray(ArrayNode::from($nullNode, []));

        self::assertInstanceOf(TreeNode::class, $aTreeNode);
        self::assertInstanceOf(ArrayNode::class, $aTreeNode->jsonNode());
        self::assertTrue($aTreeNode->isInternal());
    }

    /**
     * @test
     */
    public function it_creates_from_leaf_json_node(): void
    {
        $nullNode = new NullNode(new NodeKey('key'), new NodePath('path'));

        $nTreeNode = TreeNode::leaf($nullNode);
        $bTreeNode = TreeNode::leaf(BoolNode::from($nullNode, true));
        $iTreeNode = TreeNode::leaf(IntNode::from($nullNode, 1234));
        $fTreeNode = TreeNode::leaf(FloatNode::from($nullNode, 12.34));
        $sTreeNode = TreeNode::leaf(StringNode::from($nullNode, 'val'));

        self::assertInstanceOf(TreeNode::class, $nTreeNode);
        self::assertInstanceOf(TreeNode::class, $bTreeNode);
        self::assertInstanceOf(TreeNode::class, $iTreeNode);
        self::assertInstanceOf(TreeNode::class, $fTreeNode);
        self::assertInstanceOf(TreeNode::class, $sTreeNode);

        self::assertInstanceOf(NullNode::class, $nTreeNode->jsonNode());
        self::assertInstanceOf(BoolNode::class, $bTreeNode->jsonNode());
        self::assertInstanceOf(IntNode::class, $iTreeNode->jsonNode());
        self::assertInstanceOf(FloatNode::class, $fTreeNode->jsonNode());
        self::assertInstanceOf(StringNode::class, $sTreeNode->jsonNode());

        self::assertFalse($nTreeNode->isInternal());
        self::assertFalse($bTreeNode->isInternal());
        self::assertFalse($iTreeNode->isInternal());
        self::assertFalse($fTreeNode->isInternal());
        self::assertFalse($sTreeNode->isInternal());
    }
}
