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
use Pwm\JGami\Tree\Node;
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
        $wrapper = function (Node $node) use ($f): Node {
            if ($node->isInternal()) {
                return $node;
            }
            $oldJsonNode = $node->getJsonNode();
            $newJsonNode = $f($oldJsonNode);
            self::enforceSameKeyAndPath($oldJsonNode, $newJsonNode);
            return Node::leaf($newJsonNode);
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

            switch (JsonType::fromVal($val)->val()) {
                case JsonType::OBJECT:
                    $jsonNode = new ObjectNode(new NodeKey($key), new NodePath($path), $val);
                    $node = count((array)$val) > 0
                        ? Node::internalObject($jsonNode)
                        : Node::leaf($jsonNode);
                    return [$node, (array)$val];
                case JsonType::ARRAY:
                    $jsonNode = new ArrayNode(new NodeKey($key), new NodePath($path), $val);
                    $node = count($val) > 0
                        ? Node::internalArray($jsonNode)
                        : Node::leaf($jsonNode);
                    return [$node, $val];
                case JsonType::BOOL:
                    return [Node::leaf(new BoolNode(new NodeKey($key), new NodePath($path), $val)), []];
                case JsonType::INT:
                    return [Node::leaf(new IntNode(new NodeKey($key), new NodePath($path), $val)), []];
                case JsonType::FLOAT:
                    return [Node::leaf(new FloatNode(new NodeKey($key), new NodePath($path), $val)), []];
                case JsonType::STRING:
                    return [Node::leaf(new StringNode(new NodeKey($key), new NodePath($path), $val)), []];
                case JsonType::NULL:
                default:
                    return [Node::leaf(new NullNode(new NodeKey($key), new NodePath($path))), []];
            }
        };
    }

    // () -> Node a -> [b] -> b
    private static function foldKVPair(): Closure
    {
        return function (Node $node, array $acc): array {
            if ($node->isInternal()) {
                $val = $node->getJsonNode() instanceof ObjectNode
                    ? (object)array_merge(...$acc)
                    : array_merge(...$acc);
            } else {
                $val = $node->getJsonNode()->val();
            }
            return [$node->getJsonNode()->key()->val() => $val];
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
