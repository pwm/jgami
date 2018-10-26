<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JStringTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JString('foo');

        self::assertInstanceOf(JString::class, $jVal);
        self::assertSame('foo', $jVal->val());
    }
}
