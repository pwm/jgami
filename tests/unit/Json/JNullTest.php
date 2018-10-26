<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JNullTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JNull();

        self::assertInstanceOf(JNull::class, $jVal);
        self::assertNull($jVal->val());
    }
}
