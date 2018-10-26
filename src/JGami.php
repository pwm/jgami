<?php
declare(strict_types=1);

namespace Pwm\JGami;

use Closure;
use Pwm\JGami\Json\JArray;
use Pwm\JGami\Json\JBool;
use Pwm\JGami\Json\JFloat;
use Pwm\JGami\Json\JInt;
use Pwm\JGami\Json\JNull;
use Pwm\JGami\Json\JObject;
use Pwm\JGami\Json\JString;
use Pwm\JGami\Json\JType;
use Pwm\JGami\Json\JVal;
use Pwm\JGami\Tree\Node;
use Pwm\JGami\Tree\NodeKey;
use Pwm\JGami\Tree\NodePath;
use Pwm\JGami\Tree\TaggedNode;
use Pwm\Treegami\Tree;
use function array_merge;
use function count;

final class JGami
{
    // The key of the root node of a json structure
    public const ROOT_KEY = 'root';
    // Symbol to separate node keys in a node path
    public const PATH_SEPARATOR = '.';

    // (Node a -> JVal b) -> Json a -> Json b
    public static function map(Closure $f, $json)
    {
        // TaggedNode a -> TaggedNode b
        $wrapper = function (TaggedNode $taggedNode) use ($f): TaggedNode {
            if ($taggedNode->isInternal()) {
                return $taggedNode;
            }
            $node = $taggedNode->node();

            // Wrapping the user function to enforce well-typed IO
            // Node a -> JVal b
            $jVal = (function (Node $node) use ($f): JVal {
                return $f($node);
            })($node);

            return TaggedNode::leaf(new Node($node->key(), $node->path(), $jVal));
        };

        $jType = JType::fromVal($json);
        $keyPathValTupleList = $jType->eq(JType::OBJECT) || $jType->eq(JType::ARRAY)
            ? [self::ROOT_KEY, self::ROOT_KEY, self::toKeyPathValTupleList($json, self::ROOT_KEY)]
            : [self::ROOT_KEY, self::ROOT_KEY, $json];

        return
            Tree::unfold(self::unfoldKVPair(), $keyPathValTupleList)
                ->map($wrapper)
                ->fold(self::foldKVPair())[self::ROOT_KEY];
    }

    // (Map k v, String p) -> [(k, p, v)]
    private static function toKeyPathValTupleList($json, string $path)
    {
        $keyPathValTupleList = [];
        foreach ($json as $key => $val) {
            $childType = JType::fromVal($val);
            if ($childType->eq(JType::OBJECT) || $childType->eq(JType::ARRAY)) {
                $keyPathValTuple = [
                    $key,
                    self::extendPath($path, (string)$key),
                    self::toKeyPathValTupleList($val, self::extendPath($path, (string)$key)),
                ];
                $keyPathValTupleList[] = $childType->eq(JType::OBJECT)
                    ? (object)$keyPathValTuple
                    : $keyPathValTuple;
            } else {
                $keyPathValTupleList[] = [$key, self::extendPath($path, (string)$key), $val];
            }
        }
        return JType::fromVal($json)->eq(JType::OBJECT)
            ? (object)$keyPathValTupleList
            : $keyPathValTupleList;
    }

    // () -> b -> (TaggedNode a, [b])
    private static function unfoldKVPair(): Closure
    {
        return function ($keyPathValTuple): array {
            [$key, $path, $val] = (array)$keyPathValTuple;
            switch (JType::fromVal($val)->val()) {
                case JType::OBJECT:
                    $oNode = new Node(new NodeKey($key), new NodePath($path), new JObject($val));
                    $taggedNode = count((array)$val) > 0
                        ? TaggedNode::internal($oNode)
                        : TaggedNode::leaf($oNode);
                    return [$taggedNode, (array)$val];
                case JType::ARRAY:
                    $aNode = new Node(new NodeKey($key), new NodePath($path), new JArray($val));
                    $taggedNode = count($val) > 0
                        ? TaggedNode::internal($aNode)
                        : TaggedNode::leaf($aNode);
                    return [$taggedNode, $val];
                case JType::BOOL:
                    return [TaggedNode::leaf(new Node(new NodeKey($key), new NodePath($path), new JBool($val))), []];
                case JType::INT:
                    return [TaggedNode::leaf(new Node(new NodeKey($key), new NodePath($path), new JInt($val))), []];
                case JType::FLOAT:
                    return [TaggedNode::leaf(new Node(new NodeKey($key), new NodePath($path), new JFloat($val))), []];
                case JType::STRING:
                    return [TaggedNode::leaf(new Node(new NodeKey($key), new NodePath($path), new JString($val))), []];
                case JType::NULL:
                default:
                    return [TaggedNode::leaf(new Node(new NodeKey($key), new NodePath($path), new JNull())), []];
            }
        };
    }

    // () -> TaggedNode a -> [b] -> b
    private static function foldKVPair(): Closure
    {
        return function (TaggedNode $taggedNode, array $acc): array {
            $node = $taggedNode->node();
            if ($taggedNode->isInternal()) {
                $val = $node->jVal() instanceof JObject
                    ? (object)array_merge(...$acc)
                    : array_merge(...$acc);
            } else {
                $val = $node->jVal()->val();
            }
            return [$node->key()->val() => $val];
        };
    }

    // String -> String -> String
    private static function extendPath(string $path, string $step): string
    {
        return $path . self::PATH_SEPARATOR . $step;
    }
}
