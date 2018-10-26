<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JIntTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JInt(1234);

        self::assertInstanceOf(JInt::class, $jVal);
        self::assertSame(1234, $jVal->val());
    }
}
