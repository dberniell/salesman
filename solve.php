<?php

require_once "./functions.php";

$citiesCoordinates = parseFile();

$graph = getGraphOfDistances($citiesCoordinates);

// Use dijkstra algorythm to get an array of Cities with
// the distance from the first node till each one and the parent city node
// label[cityName] = [distanceFromFirstNode, parentNode]
$labels = dijkstra($graph, "Beijing");

//sort $labels by distance
uasort($labels, "sortByDistance");

// print list odf cities order by the order in which will be
// visited to cover the shortest distance traveled
foreach ($labels as $node => $label) {
    fwrite(STDOUT, $node . "\n");
}
