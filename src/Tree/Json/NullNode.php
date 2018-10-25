<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class NullNode implements JsonNode
{
    /** @var NodeKey */
    private $key;
    /** @var NodePath */
    private $path;

    public function __construct(NodeKey $key, NodePath $path)
    {
        $this->key = $key;
        $this->path = $path;
    }

    public static function from(JsonNode $node): self
    {
        return new self($node->key(), $node->path());
    }

    public function key(): NodeKey
    {
        return $this->key;
    }

    public function path(): NodePath
    {
        return $this->path;
    }

    public function val()
    {
        return null;
    }
}
