<?php
declare(strict_types=1);

namespace Pwm\JGami;

use Closure;
use Pwm\JGami\Exception\IntegrityViolation;
use Pwm\JGami\Tree\Json\ArrayNode;
use Pwm\JGami\Tree\Json\BoolNode;
use Pwm\JGami\Tree\Json\FloatNode;
use Pwm\JGami\Tree\Json\IntNode;
use Pwm\JGami\Tree\Json\JsonNode;
use Pwm\JGami\Tree\Json\NullNode;
use Pwm\JGami\Tree\Json\ObjectNode;
use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;
use Pwm\JGami\Tree\Json\StringNode;
use Pwm\JGami\Tree\TreeNode;
use Pwm\JGami\Type\JsonType;
use Pwm\Treegami\Tree;
use function array_merge;
use function count;

final class JGami
{
    // The key of the root node of a json structure
    public const ROOT_KEY = 'root';
    // Symbol to separate node keys in a node path
    public const PATH_SEPARATOR = '.';

    // (JsonNode a -> JsonNode b) -> Json a -> Json b
    public static function map(Closure $f, $json)
    {
        // Node a -> Node b
        $wrapper = function (TreeNode $treeNode) use ($f): TreeNode {
            if ($treeNode->isInternal()) {
                return $treeNode;
            }
            $oldJsonNode = $treeNode->jsonNode();
            $newJsonNode = $f($oldJsonNode);
            self::enforceSameKeyAndPath($oldJsonNode, $newJsonNode);
            return TreeNode::leaf($newJsonNode);
        };

        $jsonType = JsonType::fromVal($json);
        $kvPairList = $jsonType->eq(JsonType::OBJECT) || $jsonType->eq(JsonType::ARRAY)
            ? [self::ROOT_KEY, self::ROOT_KEY, self::mapToKVPairList($json, self::ROOT_KEY)]
            : [self::ROOT_KEY, self::ROOT_KEY, $json];

        return
            Tree::unfold(self::unfoldKVPair(), $kvPairList)
                ->map($wrapper)
                ->fold(self::foldKVPair())[self::ROOT_KEY];
    }

    // Map k v -> [(k, v)]
    private static function mapToKVPairList($json, string $path)
    {
        $kvPairList = [];
        $jsonType = JsonType::fromVal($json);
        foreach ($json as $key => $val) {
            $valType = JsonType::fromVal($val);
            if ($valType->eq(JsonType::OBJECT) || $valType->eq(JsonType::ARRAY)) {
                $kvPair = [
                    $key,
                    self::extendPath($path, (string)$key),
                    self::mapToKVPairList($val, self::extendPath($path, (string)$key)),
                ];
                $kvPairList[] = $valType->eq(JsonType::OBJECT)
                    ? (object)$kvPair
                    : $kvPair;
            } else {
                $kvPairList[] = [$key, self::extendPath($path, (string)$key), $val];
            }
        }
        return $jsonType->eq(JsonType::OBJECT)
            ? (object)$kvPairList
            : $kvPairList;
    }

    // () -> b -> (Node a, [b])
    private static function unfoldKVPair(): Closure
    {
        return function ($kvPair): array {
            [$key, $path, $val] = (array)$kvPair;

            $nullNode = new NullNode(new NodeKey($key), new NodePath($path));
            switch (JsonType::fromVal($val)->val()) {
                case JsonType::OBJECT:
                    $jsonNode = ObjectNode::from($nullNode, $val);
                    $treeNode = count((array)$val) > 0
                        ? TreeNode::internalObject($jsonNode)
                        : TreeNode::leaf($jsonNode);
                    return [$treeNode, (array)$val];
                case JsonType::ARRAY:
                    $jsonNode = ArrayNode::from($nullNode, $val);
                    $treeNode = count($val) > 0
                        ? TreeNode::internalArray($jsonNode)
                        : TreeNode::leaf($jsonNode);
                    return [$treeNode, $val];
                case JsonType::BOOL:
                    return [TreeNode::leaf(BoolNode::from($nullNode, $val)), []];
                case JsonType::INT:
                    return [TreeNode::leaf(IntNode::from($nullNode, $val)), []];
                case JsonType::FLOAT:
                    return [TreeNode::leaf(FloatNode::from($nullNode, $val)), []];
                case JsonType::STRING:
                    return [TreeNode::leaf(StringNode::from($nullNode, $val)), []];
                case JsonType::NULL:
                default:
                    return [TreeNode::leaf($nullNode), []];
            }
        };
    }

    // () -> Node a -> [b] -> b
    private static function foldKVPair(): Closure
    {
        return function (TreeNode $treeNode, array $acc): array {
            if ($treeNode->isInternal()) {
                $val = $treeNode->jsonNode() instanceof ObjectNode
                    ? (object)array_merge(...$acc)
                    : array_merge(...$acc);
            } else {
                $val = $treeNode->jsonNode()->val();
            }
            return [$treeNode->jsonNode()->key()->val() => $val];
        };
    }

    // String -> String -> String
    private static function extendPath(string $path, string $step): string
    {
        return $path . self::PATH_SEPARATOR . $step;
    }

    // JsonNode a -> JsonNode b -> Exception ()
    private static function enforceSameKeyAndPath(JsonNode $oldJsonNode, JsonNode $newJsonNode): void
    {
        if ($oldJsonNode->key()->ne($newJsonNode->key()->val())) {
            throw new IntegrityViolation(sprintf(
                'Key "%s" differs from key "%s"',
                $newJsonNode->key()->val(),
                $oldJsonNode->key()->val()
            ));
        }
        if ($oldJsonNode->path()->ne($newJsonNode->path()->val())) {
            throw new IntegrityViolation(sprintf(
                'Path "%s" differs from path "%s"',
                $newJsonNode->path()->val(),
                $oldJsonNode->path()->val()
            ));
        }
    }
}
