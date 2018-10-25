<?php
declare(strict_types=1);

namespace Pwm\JGami\Helper;

use PHPUnit\Framework\TestCase;
use stdClass;

final class OTest extends TestCase
{
    /**
     * @test
     */
    public function f(): void
    {
        $as = [
            [],
            [null],
            [true],
            [1234],
            [12.34],
            ['a'],
            [[]],
            [new stdClass()],
            [
                'a' => null,
                'b' => true,
                'c' => 1234,
                'd' => 12.34,
                'e' => 'a',
                'f' => [],
                'g' => new stdClass(),
            ],
        ];

        foreach ($as as $a) {
            $o = O::from($a);
            self::assertInstanceOf(stdClass::class, $o);
            self::assertSame($a, (array)$o);
        }
    }
}
