<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

interface JsonNode
{
    public function key(): NodeKey;

    public function path(): NodePath;

    public function val();
}
