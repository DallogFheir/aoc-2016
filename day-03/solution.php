<?php

function is_valid_triangle($triangle)
{
    sort($triangle);
    return $triangle[0] + $triangle[1] > $triangle[2];
}

###

function part_1($input)
{
    $count = 0;
    foreach ($input as $sides) {
        if (is_valid_triangle($sides)) {
            $count++;
        }
    }

    return $count;
}

function part_2($input)
{
    $count = 0;
    for ($i = 0; $i < count($input); $i += 3) {
        for ($j = 0; $j < 3; $j++) {
            $triangle = [$input[$i][$j], $input[$i + 1][$j], $input[$i + 2][$j]];

            if (is_valid_triangle($triangle)) {
                $count++;
            }
        }
    }

    return $count;
}

###

assert(!is_valid_triangle([5, 10, 25]));

###

$file = fopen("day-3/input.txt", "r") or die("Could not open file.");
$lines = explode("\n", fread($file, filesize("day-3/input.txt")));
fclose($file);

$input = array_map(function ($line) {
    $first = (int) substr($line, 2, 3);
    $second = (int) substr($line, 7, 3);
    $third = (int) substr($line, 12, 3);

    return [$first, $second, $third];
}, $lines);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
