<?php

require "./dijkstra.php";

/**
 * Parses file putting lines into array
 *
 * @param string $fileName
 * @return array
 */
function parseFile($fileName = "cities.txt")
{
    ini_set('auto_detect_line_endings',TRUE);
    $info = [];
    $file = fopen($fileName, 'r');

    while (($line = fgetcsv($file, 1000, "\t")) !== false)
    {
        $info[] = $line;
    }

    fclose($file);

    ini_set('auto_detect_line_endings',FALSE);

    return $info;
}

function getGraphOfDistances($citiesCoordinates)
{
    foreach ($citiesCoordinates as $cityCoordinates1) {
        foreach ($citiesCoordinates as $cityCoordinates2) {
            $city1 = $cityCoordinates1[0];
            $city2 = $cityCoordinates2[0];
            $coordinates1 = array($cityCoordinates1[1], $cityCoordinates1[2]);
            $coordinates2 = array($cityCoordinates2[1], $cityCoordinates2[2]);
            $graph[$city1][$city2] = formulaVincenty($coordinates1, $coordinates2);
        }
    }

    return $graph;
}

/**
 * Vincenty formula which calculates between two points
 * of the surface of an ellipsoid of revolution. It's used the
 * WSG84 ellipsoide as model of earth.
 *
 * @param $coordinatesA
 * @param $coordinatesB
 * @return float|int
 */
function formulaVincenty($coordinatesA, $coordinatesB)
{
    $a = 6378137; // length of semi-major axis of the ellipsoid (radius at equator);
    $b = 6356752.3142; // length of semi-minor axis of the ellipsoid (radius at the poles);
    $f = 1 / 298.257223563; // flattening of the ellipsoid;
    $latitudeA = $coordinatesA[0];
    $latitudeB = $coordinatesB[0];
    $longitudeA = $coordinatesA[1];
    $longitudeB = $coordinatesB[1];
    $differenceLongitudes = deg2rad($longitudeA - $longitudeB);
    $reducedLatitudeA = atan((1 - $f) * tan(deg2rad($latitudeA)));
    $reducedLatitudeB = atan((1 - $f) * tan(deg2rad($latitudeB)));
    $differenceLongitudesAuxiliarSphere = deg2rad($differenceLongitudes);
    $differenceLongitudesAuxiliarSphere2 = 2 * pi();

    while (abs($differenceLongitudesAuxiliarSphere - $differenceLongitudesAuxiliarSphere2) > pow(10,-12)) {
        $sinA = sqrt(pow(cos($reducedLatitudeB) * sin($differenceLongitudesAuxiliarSphere), 2) +
        pow(cos($reducedLatitudeA) * sin($reducedLatitudeB) - sin($reducedLatitudeA) * cos($reducedLatitudeB) * cos
            ($differenceLongitudesAuxiliarSphere), 2));
        $cosA = sin($reducedLatitudeA) * sin($reducedLatitudeB) + cos($reducedLatitudeA) * cos($reducedLatitudeB) *
            cos($differenceLongitudesAuxiliarSphere);
        $a = atan2($sinA, $cosA);
        $sinB = cos($reducedLatitudeA) * cos($reducedLatitudeB) * sin($differenceLongitudesAuxiliarSphere);
        $cos2B = 1 - pow($sinB, 2);
        $cos2C = $cosA - 2 * sin($reducedLatitudeA) * sin($reducedLatitudeB) / $cos2B;
        $C = $f / 16 * $cos2B * (4 + $f * (4 - 3 * $cos2B));
        $differenceLongitudesAuxiliarSphere2 = $differenceLongitudesAuxiliarSphere;
        $differenceLongitudesAuxiliarSphere = $differenceLongitudes + (1 - $C) * $f * $sinB * ($a + $C * $sinA *
                ($cos2C + $C * $cosA * (-1 + 2 * pow($cos2C, 2))));
    }

    $u2 = $cos2B * ($a - $b) / pow($b, 2);
    $A = 1 + $u2 / (16384 * (4096 + $u2 * (-768 + $u2 * (320 - 175 * $u2))));
    $B = $u2 / (1024 * (256 + $u2 * (-128 + $u2 * (74 - 47 * $u2))));
    $Da = $B * $sinA * ($cos2C + $B / (4 * ($cosA * (1 - 2 * pow($cos2C, 2)) - $B / (6 * $cos2C * (-3 + 4 * pow
                            ($sinA, 2)) * (-3 + 4 * pow($cos2C, 2))
                    ))));
    $distance = $b * $A * ($a - $Da);

    return $distance;
}

function sortByDistance($array1, $array2)
{
    return $array1[0] - $array2[0];
}