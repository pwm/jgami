<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree;

use Pwm\JGami\Json\JVal;

final class Node
{
    /** @var NodeKey */
    private $key;
    /** @var NodePath */
    private $path;
    /** @var JVal */
    private $jVal;

    public function __construct(
        NodeKey $key,
        NodePath $path,
        JVal $jVal
    ) {
        $this->key = $key;
        $this->path = $path;
        $this->jVal = $jVal;
    }

    public function key(): NodeKey
    {
        return $this->key;
    }

    public function path(): NodePath
    {
        return $this->path;
    }

    public function jVal(): JVal
    {
        return $this->jVal;
    }
}
