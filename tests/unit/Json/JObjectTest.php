<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @group Json
 */
final class JObjectTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JObject(new stdClass());

        self::assertInstanceOf(JObject::class, $jVal);
        self::assertEquals(new stdClass(), $jVal->val());
    }
}
