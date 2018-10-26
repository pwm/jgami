<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JArrayTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JArray([]);

        self::assertInstanceOf(JArray::class, $jVal);
        self::assertSame([], $jVal->val());
    }
}
