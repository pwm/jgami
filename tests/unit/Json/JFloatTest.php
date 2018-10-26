<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JFloatTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JFloat(12.34);

        self::assertInstanceOf(JFloat::class, $jVal);
        self::assertSame(12.34, $jVal->val());
    }
}
