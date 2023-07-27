<?php

declare(strict_types=1);

namespace Vertilia\Algo\TopoSort;

use UnexpectedValueException;

class TopoSort
{
    protected array $nodes = [];
    protected array $stack = [];
    protected array $sorted = [];

    /**
     * @param ?array $nodes format: {"index1": ["indexM", ...]}, ...}
     */
    public function __construct(array $nodes = [])
    {
        $this->nodes = $nodes;
    }

    /** add node with all children
     *
     * @param int|string $index
     * @param ?array $children indexes of child nodes. format: ["indexM", ...]
     * @return self
     */
    public function addNode($index, array $children = []): self
    {
        $this->nodes[$index] = $children;
        return $this;
    }

    /** add link from source to target node, also add target node as empty if not set
     *
     * @param int|string $src source node
     * @param int|string $trg target node
     * @return self
     */
    public function addLink($src, $trg): self
    {
        if (!isset($this->nodes[$src])) {
            $this->nodes[$src] = [$trg];
        } elseif (!in_array($trg, $this->nodes[$src])) {
            $this->nodes[$src][] = $trg;
        }

        if (!isset($this->nodes[$trg])) {
            $this->nodes[$trg] = [];
        }

        return $this;
    }

    /** recursively traverse a node and add to sorted array
     *
     * @param int|string $index
     * @return void
     */
    protected function processNode($index)
    {
        if (isset($this->stack[$index])) {
            throw new UnexpectedValueException("Cycle found in tree graph for index $index");
        } elseif (!isset($this->stack[$index])) {
            $this->stack[$index] = true;
            foreach ($this->nodes[$index] as $id) {
                $this->processNode($id);
            }
            unset($this->stack[$index]);
            $this->sorted[$index] = true;
        }
    }

    /** return ordered list of indexes
     *
     * @return array
     */
    public function sort(): array
    {
        foreach ($this->nodes as $index => $children) {
            $this->processNode($index);
        }

        return array_reverse(array_keys($this->sorted));
    }
}
