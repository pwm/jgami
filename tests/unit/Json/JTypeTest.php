<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @group Json
 */
final class JTypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_valid_json_types_and_has_equality(): void
    {

        foreach (self::typeValMap() as $expectedType => $jsonType) {
            $nodeType = JType::fromVal($jsonType);
            self::assertInstanceOf(JType::class, $nodeType);
            self::assertTrue($nodeType->eq($expectedType));
            self::assertFalse($nodeType->ne($expectedType));
        }
    }

    /**
     * @test
     * @expectedException \TypeError
     */
    public function it_throws_on_invalid_type(): void
    {
        JType::fromVal(function () { });
    }

    private static function typeValMap(): array
    {
        return [
            JType::OBJECT => new stdClass(),
            JType::ARRAY  => [],
            JType::NULL   => null,
            JType::BOOL   => true,
            JType::INT    => 1234,
            JType::FLOAT  => 12.34,
            JType::STRING => 'val',
        ];
    }
}
