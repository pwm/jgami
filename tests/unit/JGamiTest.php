<?php
declare(strict_types=1);

namespace Pwm\JGami;

use PHPUnit\Framework\TestCase;
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
use stdClass;
use Throwable;

final class JGamiTest extends TestCase
{
    /**
     * @test
     */
    public function mapping_id_preserves_json_values(): void
    {
        $id = function (JsonNode $node): JsonNode {
            return $node;
        };

        foreach (self::getJsonStrings() as [$initialJsonString, $_]) {
            self::assertSame($initialJsonString, json_encode(JGami::map($id, json_decode($initialJsonString))));
        }
    }

    /**
     * @test
     */
    public function mapping_a_function_modifies_json_values(): void
    {
        $transform = function (JsonNode $node): JsonNode {
            if ($node instanceof ObjectNode) {
                $node = ArrayNode::from($node, []);
            } elseif ($node instanceof ArrayNode) {
                $node = ObjectNode::from($node, new stdClass());
            } elseif ($node instanceof NullNode) {
                $node = NullNode::from($node);
            } elseif ($node instanceof BoolNode) {
                $node = BoolNode::from($node, ! $node->val());
            } elseif ($node instanceof IntNode) {
                $node = IntNode::from($node, $node->val() + 1);
            } elseif ($node instanceof FloatNode) {
                $node = FloatNode::from($node, $node->val() * 2);
            } elseif ($node instanceof StringNode) {
                $node = StringNode::from($node, strtoupper($node->val()));
            }
            return $node;
        };

        foreach (self::getJsonStrings() as [$initialJsonString, $expectedJsonString]) {
            $actualJsonString = json_encode(JGami::map($transform, json_decode($initialJsonString)));
            self::assertSame($expectedJsonString, $actualJsonString);
        }
    }

    /**
     * @test
     */
    public function changing_keys__or_paths_violates_structural_integrity(): void
    {
        $jsonString = '{"key": 1}';

        // This is the only way to violate key/path integrity, ie. to purposefully
        // use a new NullNode with custom key/path as a template
        $changeKey = function (JsonNode $node): JsonNode {
            return IntNode::from(new NullNode(new NodeKey('new-key'), $node->path()), 2);
        };
        $changePath = function (JsonNode $node): JsonNode {
            return IntNode::from(new NullNode($node->key(), new NodePath('new-path')), 2);
        };

        try {
            JGami::map($changeKey, json_decode($jsonString));
            self::assertTrue(false);
        } catch (Throwable $e) {
            self::assertInstanceOf(IntegrityViolation::class, $e);
        }

        try {
            JGami::map($changePath, json_decode($jsonString));
            self::assertTrue(false);
        } catch (Throwable $e) {
            self::assertInstanceOf(IntegrityViolation::class, $e);
        }
    }

    private static function getJsonStrings(): array
    {
        return [
            //
            ['null', 'null'],
            ['true', 'false'],
            ['false', 'true'],
            ['1', '2'],
            ['12.34', '24.68'],
            ['"a"', '"A"'],
            //
            ['[]', '{}'],
            ['[null]', '[null]'],
            ['[true]', '[false]'],
            ['[false]', '[true]'],
            ['[1]', '[2]'],
            ['[12.34]', '[24.68]'],
            ['["a"]', '["A"]'],
            //
            ['{}', '[]'],
            ['{"a":null}', '{"a":null}'],
            ['{"a":true}', '{"a":false}'],
            ['{"a":false}', '{"a":true}'],
            ['{"a":1}', '{"a":2}'],
            ['{"a":12.34}', '{"a":24.68}'],
            ['{"a":"b"}', '{"a":"B"}'],
            ['[null,true,false,1,"a"]', '[null,false,true,2,"A"]'],
            ['{"a":null,"b":true,"c":false,"d":1,"e":"f"}', '{"a":null,"b":false,"c":true,"d":2,"e":"F"}'],
            //
            ['[[],[],[],[]]', '[{},{},{},{}]'],
            ['[{},{},{},{}]', '[[],[],[],[]]'],
            ['[{},[],{},[]]', '[[],{},[],{}]'],
            //
            ['{"a":{},"b":{},"c":{},"d":{}}', '{"a":[],"b":[],"c":[],"d":[]}'],
            ['{"a":[],"b":[],"c":[],"d":[]}', '{"a":{},"b":{},"c":{},"d":{}}'],
            ['{"a":[],"b":{},"c":[],"d":{}}', '{"a":{},"b":[],"c":{},"d":[]}'],
            //
            ['[[1],{"a":2},[3],{"b":4}]', '[[2],{"a":3},[4],{"b":5}]'],
            ['{"a":{"b":1},"c":[2],"d":{"e":3},"f":[4]}', '{"a":{"b":2},"c":[3],"d":{"e":4},"f":[5]}'],
            //
            ['[[[[[[[[[["a"]]]]]]]]]]', '[[[[[[[[[["A"]]]]]]]]]]'],
            ['{"a":{"b":{"c":{"d":{"e":{"f":{"g":{"h":{"i":{"j":"k"}}}}}}}}}}', '{"a":{"b":{"c":{"d":{"e":{"f":{"g":{"h":{"i":{"j":"K"}}}}}}}}}}'],
        ];
    }
}
