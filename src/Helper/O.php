<?php
declare(strict_types=1);

namespace Pwm\JGami\Helper;

use stdClass;

final class O
{
    public static function from(array $a): stdClass
    {
        $o = new stdClass();
        foreach ($a as $k => $v) {
            $o->{$k} = $v;
        }
        return $o;
    }
}
