<?php
declare(strict_types=1);

namespace Pwm\JGami;

use Closure;
use Pwm\JGami\Node\InternalNode;
use Pwm\JGami\Node\Json\ArrayNode;
use Pwm\JGami\Node\Json\SimpleNode;
use Pwm\JGami\Node\Json\ObjectNode;
use Pwm\JGami\Node\LeafNode;
use Pwm\JGami\Node\TreeNode;
use Pwm\Treegami\Tree;
use stdClass;
use function array_merge;
use function is_array;
use function count;

final class JGami
{
    // The key of the root node of a json structure
    public const ROOT = 'root';

    // (JsonNode a -> JsonNode b) -> Json a -> Json b
    public static function mapLeaves(Closure $f, $json)
    {
        return self::map(function (TreeNode $treeNode) use ($f): TreeNode {
            if ($treeNode instanceof InternalNode) {
                return $treeNode;
            }

            $jsonNode = $f($treeNode->getJsonNode());
            if ($jsonNode instanceof ObjectNode) {
                $treeNode = LeafNode::objectNode($jsonNode);
            } elseif ($jsonNode instanceof ArrayNode) {
                $treeNode = LeafNode::arrayNode($jsonNode);
            } else {
                $treeNode = LeafNode::simpleNode($jsonNode);
            }
            return $treeNode;
        }, $json);
    }

    // (TreeNode a -> TreeNode b) -> Json a -> Json b
    public static function map(Closure $f, $json)
    {
        $kvPairList = $json instanceof stdClass || is_array($json)
            ? [self::ROOT, self::mapToKVPairList($json)]
            : [self::ROOT, $json];

        return
            Tree::unfold(self::unfoldKVPair(), $kvPairList)
                ->map($f)
                ->fold(self::foldKVPair())[self::ROOT];
    }

    // Map k v -> [(k, v)]
    private static function mapToKVPairList($json)
    {
        $kvPairList = [];
        foreach ($json as $key => $val) {
            if ($val instanceof stdClass) {
                $kvPairList[] = (object)[$key, self::mapToKVPairList($val)];
            } elseif (is_array($val)) {
                $kvPairList[] = [$key, self::mapToKVPairList($val)];
            } else {
                $kvPairList[] = [$key, $val];
            }
        }
        return $json instanceof stdClass
            ? (object)$kvPairList
            : $kvPairList;
    }

    // () -> b -> (TreeNode a, [b])
    public static function unfoldKVPair(): Closure
    {
        return function ($kvPair): array {
            [$key, $val] = (array)$kvPair;
            if ($val instanceof stdClass) {
                $treeNode = count((array)$val) > 0
                    ? InternalNode::objectNode(new ObjectNode($key, $val))
                    : LeafNode::objectNode(new ObjectNode($key, $val));
                return [$treeNode, (array)$val];
            }
            if (is_array($val)) {
                $treeNode = count($val) > 0
                    ? InternalNode::arrayNode(new ArrayNode($key, $val))
                    : LeafNode::arrayNode(new ArrayNode($key, $val));
                return [$treeNode, $val];
            }
            $treeNode = LeafNode::simpleNode(new SimpleNode($key, $val));
            return [$treeNode, []];
        };
    }

    // () -> TreeNode a -> [b] -> b
    public static function foldKVPair(): Closure
    {
        return function (TreeNode $treeNode, array $acc): array {
            if ($treeNode instanceof InternalNode) {
                $val = $treeNode->getJsonNode() instanceof ObjectNode
                    ? (object)array_merge(...$acc)
                    : array_merge(...$acc);
            } else {
                $val = $treeNode->getJsonNode()->getValue();
            }
            return [$treeNode->getJsonNode()->getKey() => $val];
        };
    }
}
