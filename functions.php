<?php

/**
 * Parses file putting lines into array
 *
 * @param string $fileName
 * @return array
 */
function parseFile($fileName = "cities.txt")
{
    $info = [];
    $file = fopen($fileName, 'r');

    while (!feof($file))
    {
        $info[] = fgetcsv($file, 2048, "\t");
    }

    fclose($file);

    return $info;
}

function formulaVincenty()
{

}
