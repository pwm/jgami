<?php
declare(strict_types=1);

namespace Pwm\JGami\Json;

use PHPUnit\Framework\TestCase;

/**
 * @group Json
 */
final class JBoolTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $jVal = new JBool(true);

        self::assertInstanceOf(JBool::class, $jVal);
        self::assertTrue($jVal->val());
    }
}
