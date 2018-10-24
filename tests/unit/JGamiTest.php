<?php
declare(strict_types=1);

namespace Pwm\JGami;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Node\Json\JsonNode;
use Pwm\JGami\Node\Json\SimpleNode;
use Pwm\JGami\Node\TreeNode;

final class JGamiTest extends TestCase
{
    /**
     * @test
     */
    public function mapping_id_preserves_json_structures(): void
    {
        $id = function (TreeNode $treeNode): TreeNode {
            return $treeNode;
        };

        foreach (self::getJsonStrings() as [$initialJsonString, $_]) {
            self::assertSame($initialJsonString, json_encode(JGami::map($id, json_decode($initialJsonString))));
        }
    }

    /**
     * @test
     */
    public function mapping_a_transformer_over_leaves_modifies_json_structures(): void
    {
        $transform = function (JsonNode $jsonNode): JsonNode {
            if (\is_bool($jsonNode->getValue())) {
                $jsonNode = new SimpleNode($jsonNode->getKey(), ! $jsonNode->getValue());
            } elseif (\is_int($jsonNode->getValue())) {
                $jsonNode = new SimpleNode($jsonNode->getKey(), $jsonNode->getValue() + 1);
            } elseif (\is_string($jsonNode->getValue())) {
                $jsonNode = new SimpleNode($jsonNode->getKey(), strtoupper($jsonNode->getValue()));
            }
            return $jsonNode;
        };

        foreach (self::getJsonStrings() as [$initialJsonString, $expectedJsonString]) {
            $actualJsonString = json_encode(JGami::mapLeaves($transform, json_decode($initialJsonString)));
            self::assertSame($expectedJsonString, $actualJsonString);
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
            ['"a"', '"A"'],
            //
            ['[]', '[]'],
            ['[null]', '[null]'],
            ['[true]', '[false]'],
            ['[false]', '[true]'],
            ['[1]', '[2]'],
            ['["a"]', '["A"]'],
            //
            ['{}', '{}'],
            ['{"a":null}', '{"a":null}'],
            ['{"a":true}', '{"a":false}'],
            ['{"a":false}', '{"a":true}'],
            ['{"a":1}', '{"a":2}'],
            ['{"a":"b"}', '{"a":"B"}'],
            ['[null,true,false,1,"a"]', '[null,false,true,2,"A"]'],
            ['{"a":null,"b":true,"c":false,"d":1,"e":"f"}', '{"a":null,"b":false,"c":true,"d":2,"e":"F"}'],
            //
            ['[[],[],[],[]]', '[[],[],[],[]]'],
            ['[{},{},{},{}]', '[{},{},{},{}]'],
            ['[{},[],{},[]]', '[{},[],{},[]]'],
            //
            ['{"a":{},"b":{},"c":{},"d":{}}', '{"a":{},"b":{},"c":{},"d":{}}'],
            ['{"a":[],"b":[],"c":[],"d":[]}', '{"a":[],"b":[],"c":[],"d":[]}'],
            ['{"a":[],"b":{},"c":[],"d":{}}', '{"a":[],"b":{},"c":[],"d":{}}'],
            //
            ['[[1],{"a":2},[3],{"b":4}]', '[[2],{"a":3},[4],{"b":5}]'],
            ['{"a":{"b":1},"c":[2],"d":{"e":3},"f":[4]}', '{"a":{"b":2},"c":[3],"d":{"e":4},"f":[5]}'],
            //
            ['[[[[[[[[[["a"]]]]]]]]]]', '[[[[[[[[[["A"]]]]]]]]]]'],
            ['{"a":{"b":{"c":{"d":{"e":{"f":{"g":{"h":{"i":{"j":"k"}}}}}}}}}}', '{"a":{"b":{"c":{"d":{"e":{"f":{"g":{"h":{"i":{"j":"K"}}}}}}}}}}'],
        ];
    }
}
