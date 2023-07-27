<?php

namespace Vertilia\Algo\TopoSort\Test;

use PHPUnit\Framework\TestCase;
use Vertilia\Algo\TopoSort\TopoSort;

/**
 * @coversDefaultClass TopoSort
 */
class TopoSortTest extends TestCase
{
    /** assert that sorted values are ordered in such a way that children elements do not appear before corresponding
     * elements in sorted array
     *
     * @param $values
     * @param $sorted
     * @return void
     */
    public function assertSorted($values, $sorted): void
    {
        foreach ($values as $index => $children) {
            $sorted_pos = array_search($index, $sorted, true);
            if ($sorted_pos) {
                $sorted_before = array_slice($sorted, 0, $sorted_pos);
                $this->assertEmpty(array_intersect($sorted_before, $children));
            }
        }
    }

    /**
     * @dataProvider providerSort
     * @covers TopoSort::sort
     */
    public function testSort($values)
    {
        // values set in constructor
        $ar1 = new TopoSort($values);
        $this->assertSorted($values, $ar1->sort());

        // values set via addNode calls
        $ar2 = new TopoSort();
        foreach ($values as $index => $children) {
            $ar2->addNode($index, $children);
        }
        $this->assertSorted($values, $ar2->sort());

        // values set via addLink calls
        $ar3 = new TopoSort();
        foreach ($values as $index => $children) {
            foreach ($children as $child) {
                $ar3->addLink($index, $child);
            }
        }
        $this->assertSorted($values, $ar3->sort());
    }

    public function providerSort(): array
    {
        return [
            'wiki[ru] 1' => [['7' => ['11', '8'], '5' => ['11'], '3' => ['8', '10'], '11' => ['2', '9', '10'], '8' => ['9'], '2' => [], '9' => [], '10' => []]],
            'wiki[ru] 1`' => [['10' => [], '9' => [], '2' => [], '8' => ['9'], '11' => ['2', '9', '10'], '3' => ['8', '10'], '5' => ['11'], '7' => ['11', '8'],]],
            'wiki[ru] 2' => [['a' => ['b', 'c', 'd', 'e'], 'b' => ['d'], 'c' => ['d', 'e'], 'd' => ['e'], 'e' => []]],
            'wiki[ru] 2`' => [['e' => [], 'd' => ['e'], 'c' => ['d', 'e'], 'b' => ['d'], 'a' => ['b', 'c', 'd', 'e'],]],
            'wiki[fr]' => [['1' => ['2', '8'], '9' => ['8'], '2' => ['3'], '8' => [], '3' => ['6'], '4' => ['3', '5'], '7' => [], '5' => ['6'], '6' => []]],
            'wiki[fr]`' => [['6' => [], '5' => ['6'], '7' => [], '4' => ['3', '5'], '3' => ['6'], '8' => [], '2' => ['3'], '9' => ['8'], '1' => ['2', '8'],]],
            'wiki[de] 1' => [['Unterhose' => ['Hose'], 'Pullover' => ['Mantel'], 'Mantel' => [], 'Hose' => ['Mantel', 'Schuhe'], 'Unterhemd' => ['Pullover'], 'Schuhe' => [], 'Socken' => ['Schuhe']]],
            'wiki[de] 1`' => [['Socken' => ['Schuhe'], 'Schuhe' => [], 'Unterhemd' => ['Pullover'], 'Hose' => ['Mantel', 'Schuhe'], 'Mantel' => [], 'Pullover' => ['Mantel'], 'Unterhose' => ['Hose'],]],
            'wiki[de] 2' => [['A' => ['B'], 'G' => ['D'], 'B' => ['C', 'E', 'D'], 'D' => ['E'], 'C' => ['E'], 'E' => ['F'], 'F' => []]],
            'wiki[de] 2`' => [['F' => [], 'E' => ['F'], 'C' => ['E'], 'D' => ['E'], 'B' => ['C', 'E', 'D'], 'G' => ['D'], 'A' => ['B'],]],
            'jon bentley ACM' => [['A' => ['B', 'F'], 'B' => ['F'], 'E' => ['B', 'D'], 'I' => ['E', 'C'], 'F' => ['H'], 'D' => ['H', 'C'], 'G' => ['E', 'C'], 'H' => [], 'C' => []]],
            'jon bentley ACM`' => [['C' => [], 'H' => [], 'G' => ['E', 'C'], 'D' => ['H', 'C'], 'F' => ['H'], 'I' => ['E', 'C'], 'E' => ['B', 'D'], 'B' => ['F'], 'A' => ['B', 'F'],]],
        ];
    }
}
