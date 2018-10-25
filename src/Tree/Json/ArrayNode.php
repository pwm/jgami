<?php
declare(strict_types=1);

namespace Pwm\JGami\Tree\Json;

use Pwm\JGami\Tree\Json\Prop\NodeKey;
use Pwm\JGami\Tree\Json\Prop\NodePath;

final class ArrayNode implements JsonNode
{
    /** @var NodeKey */
    private $key;
    /** @var NodePath */
    private $path;
    /** @var array */
    private $val;

    public static function from(JsonNode $node, array $val): self
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

    public function val(): array
    {
        return $this->val;
    }

    private function __construct(
        NodeKey $key,
        NodePath $path,
        array $val
    ) {
        $this->key = $key;
        $this->path = $path;
        $this->val = $val;
    }
}
