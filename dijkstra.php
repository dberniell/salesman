<?php

// dijkstra algorythm
function dijkstra($graph, $initialNode)
{
    $labels = [];
    $visited = [];
    $pending = [$initialNode];
    $actualNode = $initialNode;

    // intial node
    $labels[$actualNode] = [0, ''];

    // select next minor distance node accumulated
    while (count($pending) > 0) {
        $actualNode = minorDistanceNode($labels, $visited);
        $visited[] = $actualNode;

        // get adjacent nodes
        foreach ($graph[$actualNode] as $adjacent => $weight) {
            if (!in_array($adjacent, $visited) && !in_array($adjacent, $pending)) {
                $pending[] = $adjacent;
            }
            $newDistance = $labels[$actualNode][0] + $graph[$actualNode][$adjacent];

            // label
            if (!in_array($adjacent, $visited)) {
                if (!in_array($adjacent, $labels)) {
                    $labels[$adjacent] = [$newDistance, $actualNode];
                } else {
                    if ($labels[$adjacent][0] > $newDistance) {
                        $labels[$adjacent] = [$newDistance, $actualNode];
                    }
                }
            }
        }

        unset($pending[array_search($actualNode, $pending)]);
    }

    return $labels;
}

function minorDistanceNode($labels, $visited)
{
    $minor = PHP_INT_MAX;
    foreach ($labels as $node => $label) {
        if ($label[0] < $minor && !in_array($node, $visited)) {
            $minor = $label[0];
            $minorNode = $node;
        }
    }

    return $minorNode;
}