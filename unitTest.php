<?php

require "./functions.php";

use PHPUnit\Framework\TestCase;

class unitTest extends TestCase
{
    /**
     *
     */
    public function testGetGraph()
    {
        $citiesCoordinates = $this->getCitiesCoordinates();

        $graph = getGraphOfDistances($citiesCoordinates);

        $this->assertEquals(Array (
            'Beijing' => Array (
                'Beijing' => 0.0,
                'Tokyo' => 1421203.1288480773,
                'Vladivostok' => 1322422.825957729
            ),
            'Tokyo' => Array (
                'Beijing' => 1421203.1288480773,
                'Tokyo' => 0.0,
                'Vladivostok' => 931359.3666286956
            ),
            'Vladivostok' => Array (
                'Beijing' => 1322422.825957729,
                'Tokyo' => 931359.3666286956,
                'Vladivostok' => 0.0
            )
        ), $graph);

    }

    /**
     * @dataProvider getCoordinates
     */
    public function testFormulaVincenty($coordinatesA, $coordinatesB, $expectedDistance)
    {
        $distance = formulaVincenty($coordinatesA, $coordinatesB);

        $this->assertEquals($expectedDistance, $distance);
    }

    public function testDijkstra()
    {
        $citiesCoordinates = $this->getCitiesCoordinates();

        $graph = getGraphOfDistances($citiesCoordinates);

        $labels = dijkstra($graph, "Beijing");

        $this->assertEquals(array(
            'Beijing' => Array (
                0 => 0,
                1 => ''
            ),
            'Tokyo' => Array (
                0 => 2253782.1925864248,
                1 => 'Vladivostok'
            ),
            'Vladivostok' => Array (
                0 => 1322422.825957729,
                1 => 'Beijing'
            )
        ), $labels);
    }

    public function getCitiesCoordinates()
    {
        return [
            ["Beijing", 39.93, 116.40],
            ["Tokyo", 35.40, 131.54],
            ["Vladivostok", 43.8, 131.54]
        ];
    }

    public function getCoordinates()
    {
        return [
            [[39.93, 116.40], [35.40, 131.54], 1421203.1288480773],
            [[35.40, 131.54], [43.8, 131.54], 931359.3666286956],
            [[39.93, 116.40], [43.8, 131.54], 1322422.825957729]
        ];
    }
}
?>