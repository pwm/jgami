<?php
declare(strict_types=1);

namespace Pwm\JGami;

use PHPUnit\Framework\TestCase;
use Pwm\JGami\Json\JArray;
use Pwm\JGami\Json\JBool;
use Pwm\JGami\Json\JFloat;
use Pwm\JGami\Json\JInt;
use Pwm\JGami\Json\JNull;
use Pwm\JGami\Json\JObject;
use Pwm\JGami\Json\JString;
use Pwm\JGami\Json\JVal;
use Pwm\JGami\Tree\Node;
use stdClass;

final class JGamiTest extends TestCase
{
    /**
     * @test
     */
    public function mapping_id_preserves_json_values(): void
    {
        $id = function (Node $node): JVal {
            return $node->jVal();
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
        $f = function (Node $node): JVal {
            $jVal = $node->jVal();
            if ($jVal instanceof JObject) {
                $jVal = new JArray([]);
            } elseif ($jVal instanceof JArray) {
                $jVal = new JObject(new stdClass());
            } elseif ($jVal instanceof JNull) {
                $jVal = new JNull();
            } elseif ($jVal instanceof JBool) {
                $jVal = new JBool(! $jVal->val());
            } elseif ($jVal instanceof JInt) {
                $jVal = new JInt($jVal->val() + 1);
            } elseif ($jVal instanceof JFloat) {
                $jVal = new JFloat($jVal->val() * 2);
            } elseif ($jVal instanceof JString) {
                $jVal = new JString(strtoupper($jVal->val()));
            }
            return $jVal;
        };

        foreach (self::getJsonStrings() as [$initialJsonString, $expectedJsonString]) {
            $actualJsonString = json_encode(JGami::map($f, json_decode($initialJsonString)));
            self::assertSame($expectedJsonString, $actualJsonString);
        }
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function map_function_has_to_return_a_jval(): void
    {
        $f = function ($_) {
            return 'foo'; // not a JVal
        };

        JGami::map($f, json_decode('{"a":"b"}'));
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
