<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class BoolNode implements JsonNode
{
    /** @var NodeKey */
    private $key;
    /** @var NodePath */
    private $path;
    /** @var bool */
    private $val;

    public function __construct(NodeKey $key, NodePath $path, bool $val)
    {
        $this->key = $key;
        $this->path = $path;
        $this->val = $val;
    }

    public static function from(JsonNode $node, bool $val): self
    {
        return new self($node->key(), $node->path(), $val);
    }

    public function key(): NodeKey
    {
        return $this->key;
    }

    public function path(): NodePath
    {
        return $this->path;
    }

    public function val(): bool
    {
        return $this->val;
    }
}