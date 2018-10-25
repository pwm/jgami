<?php
declare(strict_types=1);

namespace Pwm\JGami\Type;

use PHPUnit\Framework\TestCase;
use stdClass;

final class JsonTypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_valid_json_types_and_has_equality(): void
    {

        foreach (self::typeValMap() as $expectedType => $jsonType) {
            $nodeType = JsonType::fromVal($jsonType);
            self::assertInstanceOf(JsonType::class, $nodeType);
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
        JsonType::fromVal(function () { });
    }

    private static function typeValMap(): array
    {
        return [
            JsonType::OBJECT => new stdClass(),
            JsonType::ARRAY  => [],
            JsonType::NULL   => null,
            JsonType::BOOL   => true,
            JsonType::INT    => 1234,
            JsonType::FLOAT  => 12.34,
            JsonType::STRING => 'val',
        ];
    }
}
