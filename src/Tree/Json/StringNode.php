<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class StringNode implements JsonNode
{
    /** @var NodeKey */
    private $key;
    /** @var NodePath */
    private $path;
    /** @var string */
    private $val;

    public static function from(JsonNode $node, string $val): self
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

    public function val(): string
    {
        return $this->val;
    }

    private function __construct(
        NodeKey $key,
        NodePath $path,
        string $val
    ) {
        $this->key = $key;
        $this->path = $path;
        $this->val = $val;
    }
}
